package com.aplikasi.warungbude;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.text.NumberFormat;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Locale;

public class HistoryAdapter extends BaseAdapter {
    private Context context;
    private List<History> historyList;

    public HistoryAdapter(Context context, List<History> historyList) {
        this.context = context;
        this.historyList = historyList;
    }

    @Override
    public int getCount() {
        return historyList.size();
    }

    @Override
    public Object getItem(int i) {
        return historyList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.history_list, viewGroup, false);
        }

        History history = historyList.get(i);

        TextView tvDate = view.findViewById(R.id.tvDate);
        TextView tvPayment = view.findViewById(R.id.tvPayment);
        TextView tvTotal = view.findViewById(R.id.tvTotal);
        TextView tvPay = view.findViewById(R.id.tvPay);
        TextView tvChange = view.findViewById(R.id.tvChange);
        TextView tvUser = view.findViewById(R.id.tvUser);
        TextView tvCustomer = view.findViewById(R.id.tvCustomer);
        Button btnDetail = view.findViewById(R.id.btnDetail);

        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("EEEE, dd MMMM yyyy", new Locale("in", "ID"));

        tvDate.setText(LocalDate.parse(history.getDate()).format(formatter));
        tvPayment.setText(history.getPayment());
        tvUser.setText(history.getName());
        tvCustomer.setText(history.getCustomer_name());
        tvTotal.setText(formatRupiah.format(history.getTotal()).replace(",00", ""));
        tvPay.setText(formatRupiah.format(history.getPay()).replace(",00", ""));
        tvChange.setText(formatRupiah.format(history.getChange()).replace(",00", ""));

        btnDetail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(context, InvoiceActivity.class);
                intent.putExtra("transaction_id", history.getTransaction_id());
                context.startActivity(intent);
                ((Activity) context).finish();
            }
        });

        return view;
    }
}
