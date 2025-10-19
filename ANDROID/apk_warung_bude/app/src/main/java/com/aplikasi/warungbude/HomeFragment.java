package com.aplikasi.warungbude;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
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

import java.io.File;
import java.text.NumberFormat;
import java.util.Locale;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link HomeFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class HomeFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public HomeFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment HomeFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static HomeFragment newInstance(String param1, String param2) {
        HomeFragment fragment = new HomeFragment();
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

    private LinearLayout card_parent;
    private ViewGroup parent;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment

        View view = inflater.inflate(R.layout.fragment_home, container, false);
        parent = container;

        card_parent = view.findViewById(R.id.card_parent);

        loadDashboard(getContext());

        return view;
    }

    private void Alert(String title, String message, Context context, int icon){
        LayoutInflater inflater = getLayoutInflater();
        View dialogView = inflater.inflate(R.layout.custom_alert, null);

        ImageView gifView = dialogView.findViewById(R.id.dialogGif);
        TextView judul = dialogView.findViewById(R.id.title);
        TextView pesan = dialogView.findViewById(R.id.message);

        judul.setText(title);
        pesan.setText(message);

        Glide.with(this)
                .asGif()
                .load(icon)
                .into(gifView);

        AlertDialog.Builder builder = new AlertDialog.Builder(context);
        builder.setView(dialogView);
        builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dialogInterface.dismiss();
            }
        });

        AlertDialog dialog = builder.create();
        dialog.show();
    }

    private void loadDashboard(Context context){
        StringRequest request = new StringRequest(Request.Method.GET, URL.URLDasbhboard, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    card_parent.removeAllViews();
                    if (!isAdded() || getContext() == null) {
                        return;
                    }

                    JSONObject jsonObject = new JSONObject(response);

                    if(jsonObject.getString("status").equals("success")){
                        JSONObject obj = jsonObject.getJSONObject("data");
                        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));

                        // =============================
                        // 1. Omset Hari Ini
                        // =============================
                        View cardOmsetHari = LayoutInflater.from(getContext()).inflate(R.layout.card_dashboard, card_parent, false);
                        TextView tvTitle1 = cardOmsetHari.findViewById(R.id.tvTitle);
                        TextView tvAmount1 = cardOmsetHari.findViewById(R.id.tvAmount);
                        TextView tvCash1 = cardOmsetHari.findViewById(R.id.tvCash);
                        TextView tvCredit1 = cardOmsetHari.findViewById(R.id.tvCredit);

                        int omsetHariIni = obj.getInt("omsetHariIni");
                        int omsetHariIniTunai = obj.getInt("omsetHariIniTunai");
                        int omsetHariIniKredit = obj.getInt("omsetHariIniKredit");

                        tvTitle1.setText("\uD83D\uDCB0 Omset Hari Ini");
                        tvAmount1.setText(formatRupiah.format(omsetHariIni).replace(",00", ""));
                        tvCash1.setText(formatRupiah.format(omsetHariIniTunai).replace(",00", ""));
                        tvCredit1.setText(formatRupiah.format(omsetHariIniKredit).replace(",00", ""));
                        card_parent.addView(cardOmsetHari);

                        // =============================
                        // 2. Laba Hari Ini
                        // =============================
                        View cardLabaHari = LayoutInflater.from(getContext()).inflate(R.layout.card_dashboard, card_parent, false);
                        TextView tvTitle2 = cardLabaHari.findViewById(R.id.tvTitle);
                        TextView tvAmount2 = cardLabaHari.findViewById(R.id.tvAmount);
                        TextView tvCash2 = cardLabaHari.findViewById(R.id.tvCash);
                        TextView tvCredit2 = cardLabaHari.findViewById(R.id.tvCredit);

                        int labaHariIni = obj.getInt("labaHariIni");
                        int labaHariIniTunai = obj.getInt("labaHariIniTunai");
                        int labaHariIniKredit = obj.getInt("labaHariIniKredit");

                        tvTitle2.setText("\uD83D\uDCB5 Laba Hari Ini");
                        tvAmount2.setText(formatRupiah.format(labaHariIni).replace(",00", ""));
                        tvCash2.setText(formatRupiah.format(labaHariIniTunai).replace(",00", ""));
                        tvCredit2.setText(formatRupiah.format(labaHariIniKredit).replace(",00", ""));
                        card_parent.addView(cardLabaHari);

                        // =============================
                        // 3. Omset Bulan Ini
                        // =============================
                        View cardOmsetBulan = LayoutInflater.from(getContext()).inflate(R.layout.card_dashboard, card_parent, false);
                        TextView tvTitle3 = cardOmsetBulan.findViewById(R.id.tvTitle);
                        TextView tvAmount3 = cardOmsetBulan.findViewById(R.id.tvAmount);
                        TextView tvCash3 = cardOmsetBulan.findViewById(R.id.tvCash);
                        TextView tvCredit3 = cardOmsetBulan.findViewById(R.id.tvCredit);

                        int omsetBulanIni = obj.getInt("omsetBulanIni");
                        int omsetBulanIniTunai = obj.getInt("omsetBulanIniTunai");
                        int omsetBulanIniKredit = obj.getInt("omsetBulanIniKredit");

                        tvTitle3.setText("\uD83D\uDCB0 Omset Bulan Ini");
                        tvAmount3.setText(formatRupiah.format(omsetBulanIni).replace(",00", ""));
                        tvCash3.setText(formatRupiah.format(omsetBulanIniTunai).replace(",00", ""));
                        tvCredit3.setText(formatRupiah.format(omsetBulanIniKredit).replace(",00", ""));
                        card_parent.addView(cardOmsetBulan);

                        // =============================
                        // 4. Laba Bulan Ini
                        // =============================
                        View cardLabaBulan = LayoutInflater.from(getContext()).inflate(R.layout.card_dashboard, card_parent, false);
                        TextView tvTitle4 = cardLabaBulan.findViewById(R.id.tvTitle);
                        TextView tvAmount4 = cardLabaBulan.findViewById(R.id.tvAmount);
                        TextView tvCash4 = cardLabaBulan.findViewById(R.id.tvCash);
                        TextView tvCredit4 = cardLabaBulan.findViewById(R.id.tvCredit);

                        int labaBulanIni = obj.getInt("labaBulanIni");
                        int labaBulanIniTunai = obj.getInt("labaBulanIniTunai");
                        int labaBulanIniKredit = obj.getInt("labaBulanIniKredit");

                        tvTitle4.setText("\uD83D\uDCB5 Laba Bulan Ini");
                        tvAmount4.setText(formatRupiah.format(labaBulanIni).replace(",00", ""));
                        tvCash4.setText(formatRupiah.format(labaBulanIniTunai).replace(",00", ""));
                        tvCredit4.setText(formatRupiah.format(labaBulanIniKredit).replace(",00", ""));
                        card_parent.addView(cardLabaBulan);
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

                        Alert("Error", message.toString(), getContext(), R.raw.alert);
                    } catch (Exception e) {
                        e.printStackTrace();
                        Alert("Error", "Terjadi kesalahan saat membaca respon server.", getContext(), R.raw.alert);
                    }
                } else {
                    Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", getContext(), R.raw.alert);
                }
            }
        });

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}