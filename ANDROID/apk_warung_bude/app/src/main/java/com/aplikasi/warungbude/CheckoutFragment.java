package com.aplikasi.warungbude;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

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
import java.util.Locale;
import java.util.Map;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link CheckoutFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class CheckoutFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public CheckoutFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment CheckoutFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static CheckoutFragment newInstance(String param1, String param2) {
        CheckoutFragment fragment = new CheckoutFragment();
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
    private TextView tvTotal, tvChange;
    private int total = 0;
    private Spinner dropdown_payment;
    private String payment = "Pilih";
    private EditText etPay;
    private AutoCompleteTextView etCustomer;
    private LinearLayout customerPanel;
    private Button btnCheckout;
    private ArrayList<String> customer_names;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_checkout, container, false);

        Session session = new Session(getContext());
        String token = session.getData(session.TOKEN_KEY);

        backLink = view.findViewById(R.id.backLink);
        backLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new CartFragment()).commit();
            }
        });

        tvChange = view.findViewById(R.id.tvChange);
        tvTotal = view.findViewById(R.id.tvTotal);
        etCustomer = view.findViewById(R.id.etCustomer);
        if (getArguments() != null) {
            total = getArguments().getInt("total");
            tvTotal.setText(String.valueOf(total));
            tvChange.setText(String.valueOf(total * -1));

            customer_names = getArguments().getStringArrayList("customer_names");
            ArrayAdapter<String> adapter = new ArrayAdapter<>(
                    getContext(),
                    android.R.layout.simple_dropdown_item_1line,
                    customer_names
            );

            etCustomer.setAdapter(adapter);
        }

        etPay = view.findViewById(R.id.etPay);
        customerPanel = view.findViewById(R.id.customerPanel);

        etPay.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable editable) {

            }

            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                try {
                    int pay = Integer.parseInt(etPay.getText().toString());
                    int change = pay - total;
                    tvChange.setText(String.valueOf(change));
                } catch (Exception e) {
                    e.printStackTrace();
                    tvChange.setText(String.valueOf(total * -1));
                }
            }
        });

        dropdown_payment = view.findViewById(R.id.dropdown_payment);
        dropdown_payment.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                payment = adapterView.getItemAtPosition(i).toString();
                if (payment.equals("tunai")) {
                    etPay.setEnabled(true);
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.cash, 0);
                    etCustomer.setText("");
                    customerPanel.setVisibility(View.GONE);
                } else if (payment.equals("kredit")) {
                    etPay.setEnabled(false);
                    etPay.setText("0");
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.credit, 0);
                    tvChange.setText(String.valueOf(total * -1));
                    customerPanel.setVisibility(View.VISIBLE);
                } else if (payment.equals("Pilih")) {
                    etPay.setEnabled(false);
                    etPay.setText("0");
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, 0, 0);
                    tvChange.setText(String.valueOf(total * -1));
                    customerPanel.setVisibility(View.GONE);
                    etCustomer.setText("");
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {
                payment = "Pilih";
            }
        });

        btnCheckout = view.findViewById(R.id.btnCheckout);
        btnCheckout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int change = Integer.parseInt(tvChange.getText().toString());
                int pay = Integer.parseInt(etPay.getText().toString());
                if(change < 0 && pay != 0){
                    LayoutInflater inflater = LayoutInflater.from(getContext());
                    View dialogView = inflater.inflate(R.layout.dialog_customer, null);

                    TextView judul = dialogView.findViewById(R.id.title);
                    TextView pesan = dialogView.findViewById(R.id.message);
                    AutoCompleteTextView etCustomer = dialogView.findViewById(R.id.etCustomer);
                    ArrayAdapter<String> adapter = new ArrayAdapter<>(
                            getContext(),
                            android.R.layout.simple_dropdown_item_1line,
                            customer_names
                    );

                    etCustomer.setAdapter(adapter);

                    judul.setText("Uang Kurang!");
                    pesan.setText("Masukan nama pelanggan jika sisa total akan dimasukan kedalam pembayaran kredit!");

                    AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                    builder.setView(dialogView);
                    builder.setPositiveButton("Ya", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i) {
                            payment = "kredit";
                            transactionStore(getContext(), token, total, pay, change, payment, etCustomer.getText().toString());
                        }
                    });
                    builder.setNegativeButton("Tidak", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i) {
                            dialogInterface.dismiss();
                        }
                    });

                    AlertDialog dialog = builder.create();
                    dialog.show();
                } else {
                    if (payment.equals("kredit")) {
                        transactionStore(getContext(), token, total, pay, change, payment, etCustomer.getText().toString());
                    } else {
                        transactionStore(getContext(), token, total, pay, change, payment, "");
                    }
                }
            }
        });

        return view;
    }

    private void transactionStore(Context context, String token, int total, int pay, int change, String payment, String customer_name){
        StringRequest request = new StringRequest(Request.Method.POST, URL.URLTransactionStore, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    if(jsonObject.getString("status").equals("success")){
                        Toast.makeText(context, jsonObject.getString("message"), Toast.LENGTH_SHORT).show();
                        getContext().startActivity(new Intent(getActivity(), InvoiceActivity.class));
                        getActivity().finish();
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

                        Alert.Show("Error", message.toString(), context, R.raw.alert);
                    } catch (Exception e) {
                        e.printStackTrace();
                        Alert.Show("Error", "Terjadi kesalahan saat membaca respon server.", context, R.raw.alert);
                    }
                } else {
                    Alert.Show("Error", "Tidak ada koneksi internet atau server tidak merespon.", context, R.raw.alert);
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

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("total", String.valueOf(total));
                params.put("pay", String.valueOf(pay));
                params.put("change", String.valueOf(change));
                params.put("payment", payment);
                params.put("customer_name", customer_name);
                return params;
            }
        };

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}