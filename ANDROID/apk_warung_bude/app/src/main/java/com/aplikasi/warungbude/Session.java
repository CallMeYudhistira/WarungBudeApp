package com.aplikasi.warungbude;


import android.content.Context;
import android.content.SharedPreferences;

public class Session {
    private final String SharedPrefName = "user_data";
    public final String NAME_KEY = "Name";
    public final String PHONE_NUMBER_KEY = "PhoneNumber";
    public final String USERNAME_KEY = "Username";
    public final String ROLE_KEY = "Role";
    public final String TOKEN_KEY = "Token";
    private Context context;

    public Session(Context context){
        this.context = context;
    }

    public void saveUser(String name, String phone_number, String username, String role, String token){
        SharedPreferences pref = context.getSharedPreferences(SharedPrefName, Context.MODE_PRIVATE);
        pref.edit()
                .putString(NAME_KEY, name)
                .putString(PHONE_NUMBER_KEY, phone_number)
                .putString(USERNAME_KEY, username)
                .putString(ROLE_KEY, role)
                .putString(TOKEN_KEY, token)
                .apply();
    }

    public String getData(String KEY){
        SharedPreferences pref = context.getSharedPreferences(SharedPrefName, Context.MODE_PRIVATE);
        return pref.getString(KEY, null);
    }

    public void logout(){
        SharedPreferences pref = context.getSharedPreferences(SharedPrefName, Context.MODE_PRIVATE);
        pref.edit().clear().apply();
    }
}
