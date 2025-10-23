package com.aplikasi.warungbude;

import android.app.DownloadManager;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.NumberFormat;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

public class InvoiceActivity extends AppCompatActivity {

    private TextView date, user_name, customer_name, payment, tvTotal, tvPay, tvChange;
    private ListView listViewTransaction;
    private Button btnSave, btnClose;
    private List<Invoice> invoiceList;
    private InvoiceAdapter invoiceAdapter;
    private String transaction_id = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_invoice);

        date = findViewById(R.id.date);
        user_name = findViewById(R.id.user_name);
        customer_name = findViewById(R.id.customer_name);
        payment = findViewById(R.id.payment);
        tvTotal = findViewById(R.id.tvTotal);
        tvPay = findViewById(R.id.tvPay);
        tvChange = findViewById(R.id.tvChange);
        listViewTransaction = findViewById(R.id.listViewTransaction);
        btnSave = findViewById(R.id.btnSave);
        btnClose = findViewById(R.id.btnClose);
        invoiceList = new ArrayList<>();
        Session session = new Session(InvoiceActivity.this);
        String token = session.getData(session.TOKEN_KEY);

        btnClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(getApplicationContext(), ParentActivity.class));
                finish();
            }
        });

        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(!transaction_id.equals("")){
                    String url = URL.IP + "transaksi/detail/" + transaction_id + "/print";
                    DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));
                    request.setTitle("Mengunduh File PDF");
                    request.setDescription("File sedang diunduh...");
                    request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
                    request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, "transaksi_" + transaction_id + ".pdf");

                    DownloadManager manager = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                    manager.enqueue(request);
                    Toast.makeText(InvoiceActivity.this, "File disimpan di /storage/emulated/0/download/transaksi_" + transaction_id + ".pdf", Toast.LENGTH_SHORT).show();
                } else {
                    Toast.makeText(InvoiceActivity.this, "Data Transaksi Kosong!", Toast.LENGTH_SHORT).show();
                }
            }
        });

        loadInvoice(InvoiceActivity.this, token);
    }

    public void loadInvoice(Context context, String token) {
        StringRequest request = new StringRequest(Request.Method.GET, URL.URLTransactionDetail, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    transaction_id = jsonObject.getString("transaction_id");
                    if(jsonObject.getString("status").equals("success")){
                        JSONObject transaction = jsonObject.getJSONObject("transaction");
                        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
                        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("EEEE, dd MMMM yyyy", new Locale("in", "ID"));

                        LocalDate tanggal = LocalDate.parse(transaction.getString("date"));
                        String metode = transaction.getString("payment");
                        String kasir = transaction.getString("name");
                        String pelanggan = transaction.getString("customer_name");
                        int total = transaction.getInt("total");
                        int bayar = transaction.getInt("pay");
                        int kembali = transaction.getInt("change");

                        String formattedDate = tanggal.format(formatter);
                        date.setText(formattedDate);
                        payment.setText(metode);
                        user_name.setText(kasir);
                        customer_name.setText(pelanggan);
                        tvTotal.setText(formatRupiah.format(total));
                        tvPay.setText(formatRupiah.format(bayar));
                        tvChange.setText(formatRupiah.format(kembali));

                        JSONArray transaction_details = jsonObject.getJSONArray("transaction_details");

                        for (int i = 0; i < transaction_details.length(); i++) {
                            JSONObject transaction_detail = transaction_details.getJSONObject(i);

                            String product_name = transaction_detail.getString("product_name");
                            int selling_price = transaction_detail.getInt("selling_price");
                            int quantity = transaction_detail.getInt("quantity");
                            int subtotal = transaction_detail.getInt("subtotal");

                            invoiceList.add(new Invoice(product_name, selling_price, quantity, subtotal));
                        }
                        invoiceAdapter = new InvoiceAdapter(context, invoiceList);
                        listViewTransaction.setAdapter(invoiceAdapter);
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
        };

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}