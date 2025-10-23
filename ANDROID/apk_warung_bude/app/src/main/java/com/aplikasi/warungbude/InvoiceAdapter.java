package com.aplikasi.warungbude;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class InvoiceAdapter extends BaseAdapter {
    private Context context;
    private List<Invoice> invoiceList;

    public InvoiceAdapter(Context context, List<Invoice> invoiceList) {
        this.context = context;
        this.invoiceList = invoiceList;
    }

    @Override
    public int getCount() {
        return invoiceList.size();
    }

    @Override
    public Object getItem(int i) {
        return invoiceList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.invoice_list, viewGroup, false);
        }

        Invoice invoice = invoiceList.get(i);

        TextView product_name = view.findViewById(R.id.product_name);
        TextView selling_price = view.findViewById(R.id.selling_price);
        TextView quantity = view.findViewById(R.id.quantity);
        TextView subtotal = view.findViewById(R.id.subtotal);
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));

        product_name.setText(invoice.getProduct_name());
        selling_price.setText(formatRupiah.format(invoice.getSelling_price()));
        quantity.setText(String.valueOf(invoice.getQuantity()));
        subtotal.setText(formatRupiah.format(invoice.getSubtotal()));

        return view;
    }
}
