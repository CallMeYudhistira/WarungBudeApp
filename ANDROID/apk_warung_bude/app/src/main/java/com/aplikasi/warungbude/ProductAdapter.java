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

public class ProductAdapter extends BaseAdapter {
    private Context context;
    private List<Product> productList;

    public ProductAdapter(Context context, List<Product> productList) {
        this.context = context;
        this.productList = productList;
    }

    @Override
    public int getCount() {
        return productList.size();
    }

    @Override
    public Object getItem(int i) {
        return productList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.card_product, viewGroup, false);
        }

        Product product = productList.get(i);

        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        TextView tvProductName = view.findViewById(R.id.tvProductName);
        TextView tvCategoryName = view.findViewById(R.id.tvCategoryName);
        TextView tvPrice = view.findViewById(R.id.tvPrice);
        TextView tvQty = view.findViewById(R.id.tvQty);
        Button btnMin = view.findViewById(R.id.btnMin);
        Button btnAdd = view.findViewById(R.id.btnAdd);
        Button btnBuy = view.findViewById(R.id.btnBuy);
        ImageView ivPict = view.findViewById(R.id.ivPict);

        tvProductName.setText(product.getProduct_name());
        tvCategoryName.setText(product.getCategory_name());
        tvPrice.setText(formatRupiah.format(product.getSelling_price()).replace(",00", "") + product.getUnit_name());
        Glide.with(context).load(URL.URLImage + product.getPict()).into(ivPict);

        btnAdd.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int qty = Integer.parseInt(tvQty.getText().toString());
                tvQty.setText("" + (qty + 1));
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
