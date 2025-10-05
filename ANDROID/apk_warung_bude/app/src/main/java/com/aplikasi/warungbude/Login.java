package com.aplikasi.warungbude;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.bumptech.glide.Glide;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class Login extends AppCompatActivity {

    TextView tvGuestAccount, forgotPassword;
    Button btnLogin;
    EditText etUsername, etPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        tvGuestAccount = findViewById(R.id.tvGuestLink);
        forgotPassword = findViewById(R.id.tvForgot);
        btnLogin = findViewById(R.id.btnLogin);
        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);

        tvGuestAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                guestAccount();
            }
        });

        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String username = etUsername.getText().toString().trim();
                String password = etPassword.getText().toString().trim();

                if(username.isEmpty() || password.isEmpty()){
                    Alert("Error", "Field Wajib Diisi", Login.this, R.raw.alert);
                } else {
                    loginUser(username, password);
                }
            }
        });
    }

    private void guestAccount(){
        StringRequest request = new StringRequest(Request.Method.POST, URL.URLGuest, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String message = jsonObject.getString("message");

                    Toast.makeText(Login.this, message, Toast.LENGTH_SHORT).show();

                    if(jsonObject.getString("status").equals("success")){
                        JSONObject user = jsonObject.getJSONObject("data");
                        Session session = new Session(Login.this);
                        session.saveUser(user.getString("name"), user.getString("phone_number"), user.getString("role"));

                        startActivity(new Intent(getApplicationContext(), ParentActivity.class));
                        finish();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(Login.this, e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Login.this, error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });

        RequestQueue queue = Volley.newRequestQueue(this);
        queue.add(request);
    }

    private void loginUser(String username, String password) {
        StringRequest request = new StringRequest(Request.Method.POST, URL.URLLogin, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String message = jsonObject.getString("message");

                    Toast.makeText(Login.this, message, Toast.LENGTH_SHORT).show();

                    if(jsonObject.getString("status").equals("success")){
                        JSONObject user = jsonObject.getJSONObject("data");
                        Session session = new Session(Login.this);
                        session.saveUser(user.getString("name"), user.getString("phone_number"), user.getString("role"));

                        startActivity(new Intent(getApplicationContext(), ParentActivity.class));
                        finish();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(Login.this, e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Login.this, error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("username", username);
                params.put("password", password);
                return params;
            }
        };

        RequestQueue queue = Volley.newRequestQueue(this);
        queue.add(request);
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
}