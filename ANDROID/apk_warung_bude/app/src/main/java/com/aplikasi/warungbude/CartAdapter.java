package com.aplikasi.warungbude;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;

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
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

public class CartAdapter extends BaseAdapter {
    private Context context;
    private List<Cart> cartList;
    private CartFragment fragment;

    public CartAdapter(Context context, List<Cart> cartList, CartFragment fragment) {
        this.context = context;
        this.cartList = cartList;
        this.fragment = fragment;
    }

    @Override
    public int getCount() {
        return cartList.size();
    }

    @Override
    public Object getItem(int i) {
        return cartList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.card_cart, viewGroup, false);
        }

        Cart cart = cartList.get(i);

        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        TextView tvProductName = view.findViewById(R.id.tvProductName);
        TextView tvCategoryName = view.findViewById(R.id.tvCategoryName);
        TextView tvPrice = view.findViewById(R.id.tvPrice);
        TextView tvQty = view.findViewById(R.id.tvQty);
        TextView tvSubtotal = view.findViewById(R.id.tvSubtotal);
        Button btnMin = view.findViewById(R.id.btnMin);
        Button btnAdd = view.findViewById(R.id.btnAdd);
        Button btnDelete = view.findViewById(R.id.btnDelete);
        ImageView ivPict = view.findViewById(R.id.ivPict);

        int subtotal = cart.getSubtotal();
        int selling_price = cart.getSelling_price();

        Session session = new Session(context);
        String token = session.getData(session.TOKEN_KEY);
        String cart_id = cart.getCart_id();

        tvProductName.setText("📝 " + cart.getProduct_name());
        tvCategoryName.setText("🗂️ " + cart.getCategory_name());
        tvPrice.setText("💲 " + formatRupiah.format(selling_price).replace(",00", "") + "/" + cart.getUnit_name());
        tvSubtotal.setText("💵 " + formatRupiah.format(subtotal).replace(",00", ""));
        tvQty.setText("" + cart.getQuantity());
        Glide.with(context).load(URL.URLImage + cart.getPict()).into(ivPict);

        btnAdd.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int qty = Integer.parseInt(tvQty.getText().toString());
                if(qty < cart.getStock()){
                    btnAdd.setEnabled(false);

                    editCart(context, URL.URLAddQTYCart, token, cart_id);

                    btnAdd.setEnabled(true);
                }
            }
        });

        btnMin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int qty = Integer.parseInt(tvQty.getText().toString());
                if (qty > 1){
                    btnMin.setEnabled(false);

                    editCart(context, URL.URLMinQTYCart, token, cart_id);

                    btnMin.setEnabled(true);
                }
            }
        });

        btnDelete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                btnDelete.setEnabled(false);

                editCart(context, URL.URLDeleteCart, token, cart_id);

                btnDelete.setEnabled(true);
            }
        });

        return view;
    }

    private void editCart(Context context, String url, String token, String cart_id){
        StringRequest request = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    if(jsonObject.getString("status").equals("deleted")){
                        Toast.makeText(context, jsonObject.getString("message"), Toast.LENGTH_SHORT).show();
                    }

                    fragment.loadCart(context, token);
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
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("cart_id", cart_id);
                return params;
            }
        };

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}
