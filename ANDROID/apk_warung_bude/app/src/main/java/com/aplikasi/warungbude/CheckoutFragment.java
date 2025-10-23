package com.aplikasi.warungbude;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import java.text.NumberFormat;
import java.util.Locale;

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

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_checkout, container, false);

        backLink = view.findViewById(R.id.backLink);
        backLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new CartFragment()).commit();
            }
        });

        tvTotal = view.findViewById(R.id.tvTotal);
        if (getArguments() != null) {
            total = getArguments().getInt("total");
            tvTotal.setText(String.valueOf(total));
        }

        tvChange = view.findViewById(R.id.tvChange);
        etPay = view.findViewById(R.id.etPay);

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
                    tvChange.setText("0");
                }
            }
        });

        dropdown_payment = view.findViewById(R.id.dropdown_payment);
        dropdown_payment.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                payment = adapterView.getItemAtPosition(i).toString();
                if (payment.equals("Tunai")) {
                    etPay.setEnabled(true);
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.cash, 0);
                } else if (payment.equals("Kredit")) {
                    etPay.setEnabled(false);
                    etPay.setText("0");
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.credit, 0);
                    tvChange.setText("0");
                } else if (payment.equals("Pilih")) {
                    etPay.setEnabled(false);
                    etPay.setText("0");
                    etPay.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, 0, 0);
                    tvChange.setText("0");
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {
                payment = "Pilih";
            }
        });

        return view;
    }
}