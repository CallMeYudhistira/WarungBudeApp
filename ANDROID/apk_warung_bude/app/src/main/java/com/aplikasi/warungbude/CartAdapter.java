package com.aplikasi.warungbude;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class CartAdapter extends BaseAdapter {
    private Context context;
    private List<Cart> cartList;

    public CartAdapter(Context context, List<Cart> cartList) {
        this.context = context;
        this.cartList = cartList;
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
                    tvQty.setText("" + (qty + 1));
                }
            }
        });

        btnMin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int qty = Integer.parseInt(tvQty.getText().toString());
                if(qty > 1){
                    tvQty.setText("" + (qty - 1));
                }
            }
        });

        return view;
    }
}
