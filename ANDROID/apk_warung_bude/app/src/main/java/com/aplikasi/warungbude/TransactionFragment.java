package com.aplikasi.warungbude;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.bumptech.glide.Glide;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link TransactionFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class TransactionFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public TransactionFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment TransactionFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static TransactionFragment newInstance(String param1, String param2) {
        TransactionFragment fragment = new TransactionFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    private ListView listViewProduct;
    private List<Product> productList;
    private ProductAdapter productAdapter;
    private ImageView cartLink;
    private EditText etSearch;
    private ProgressBar progressBar;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_transaction, container, false);

        cartLink = view.findViewById(R.id.cartLink);
        cartLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new CartFragment()).commit();
            }
        });

        listViewProduct = view.findViewById(R.id.listViewProduct);
        productList = new ArrayList<>();

        etSearch = view.findViewById(R.id.etSearch);
        etSearch.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable editable) {

            }

            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                loadProduct(getContext(), charSequence.toString());
            }
        });

        progressBar = view.findViewById(R.id.progressBar);

        loadProduct(getContext(), "");

        return view;
    }

    private void loadProduct(Context context, String product_name){
        progressBar.setVisibility(View.VISIBLE);
        listViewProduct.setVisibility(View.GONE);
        StringRequest request = new StringRequest(Request.Method.GET, URL.URLGetProduct + product_name, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    if (!isAdded() || getContext() == null) {
                        return;
                    }

                    JSONObject jsonObject = new JSONObject(response);

                    if(jsonObject.getString("status").equals("success")){
                        JSONArray products = jsonObject.getJSONArray("products");
                        productList = new ArrayList<>();

                        for (int i = 0; i < products.length(); i++) {
                            JSONObject obj = products.getJSONObject(i);
                            String product_detail_id = obj.getString("product_detail_id");
                            String product_name = obj.getString("product_name");
                            String pict = obj.getString("pict");
                            String category_name = obj.getString("category_name");
                            String unit_name = obj.getString("unit_name");
                            int purchase_price = obj.getInt("purchase_price");
                            int selling_price = obj.getInt("selling_price");
                            int stock = obj.getInt("stock");

                            productList.add(new Product(product_detail_id, product_name, pict, category_name, unit_name, purchase_price, selling_price, stock));
                        }
                        productAdapter = new ProductAdapter(context, productList);
                        listViewProduct.setAdapter(productAdapter);

                        progressBar.setVisibility(View.GONE);
                        listViewProduct.setVisibility(View.VISIBLE);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(context, e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressBar.setVisibility(View.GONE);
                listViewProduct.setVisibility(View.VISIBLE);

                if (error.networkResponse != null && error.networkResponse.data != null) {
                    try {
                        String responseBody = new String(error.networkResponse.data, "utf-8");
                        JSONObject jsonObject = new JSONObject(responseBody);
                        JSONArray errorsArray = jsonObject.getJSONArray("message");

                        StringBuilder message = new StringBuilder();
                        for (int i = 0; i < errorsArray.length(); i++) {
                            message.append(errorsArray.getString(i));
                            if (i < errorsArray.length() - 1) {
                                message.append("\n");
                            }
                        }

                        Alert.Show("Error", message.toString(), getContext(), R.raw.alert);
                    } catch (Exception e) {
                        e.printStackTrace();
                        Alert.Show("Error", "Terjadi kesalahan saat membaca respon server.", getContext(), R.raw.alert);
                    }
                } else {
                    Alert.Show("Error", "Tidak ada koneksi internet atau server tidak merespon.", getContext(), R.raw.alert);
                }
            }
        });

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}