package com.aplikasi.warungbude;

import android.content.Context;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link CartFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class CartFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public CartFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment CartFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static CartFragment newInstance(String param1, String param2) {
        CartFragment fragment = new CartFragment();
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

    private ImageView backLink;
    private ListView listViewCart;
    private List<Cart> cartList;
    private CartAdapter cartAdapter;
    private ArrayList<String> customer_names;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_cart, container, false);

        backLink = view.findViewById(R.id.backLink);
        backLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new TransactionFragment()).commit();
            }
        });

        listViewCart = view.findViewById(R.id.listViewCart);
        cartList = new ArrayList<>();

        Session session = new Session(getContext());
        String token = session.getData(session.TOKEN_KEY);

        loadCart(getContext(), token);

        return view;
    }

    private void loadCart(Context context, String token) {
        StringRequest request = new StringRequest(Request.Method.GET, URL.URLGetCart, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    if (!isAdded() || getContext() == null) {
                        return;
                    }

                    JSONObject jsonObject = new JSONObject(response);

                    if(jsonObject.getString("status").equals("success")){
                        JSONArray products = jsonObject.getJSONArray("carts");

                        for (int i = 0; i < products.length(); i++) {
                            JSONObject obj = products.getJSONObject(i);
                            String cart_id = obj.getString("cart_id");
                            String product_name = obj.getString("product_name");
                            String pict = obj.getString("pict");
                            String category_name = obj.getString("category_name");
                            String unit_name = obj.getString("unit_name");
                            String user_name = obj.getString("name");
                            int subtotal = obj.getInt("subtotal");
                            int selling_price = obj.getInt("selling_price");
                            int quantity = obj.getInt("quantity");
                            int stock = obj.getInt("stock");

                            cartList.add(new Cart(cart_id, product_name, pict, category_name, unit_name, user_name, selling_price, quantity, subtotal, stock));
                        }
                        cartAdapter = new CartAdapter(context, cartList);
                        listViewCart.setAdapter(cartAdapter);

                        JSONArray customers = jsonObject.getJSONArray("customers");
                        customer_names = new ArrayList<>();

                        for (int i = 0; i < customers.length(); i++) {
                            JSONObject obj = customers.getJSONObject(i);
                            String customer_name = obj.getString("customer_name");

                            customer_names.add(customer_name);
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(context, e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
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
        }) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Authorization", "Bearer " + token);
                headers.put("Accept", "application/json");
                return headers;
            };
        };

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}