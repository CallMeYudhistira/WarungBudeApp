package com.aplikasi.warungbude;

import android.content.Context;
import android.content.DialogInterface;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;

import com.bumptech.glide.Glide;

public class Alert {

    public static void Show(String title, String message, Context context, int icon){
        LayoutInflater inflater = LayoutInflater.from(context);
        View dialogView = inflater.inflate(R.layout.custom_alert, null);

        ImageView gifView = dialogView.findViewById(R.id.dialogGif);
        TextView judul = dialogView.findViewById(R.id.title);
        TextView pesan = dialogView.findViewById(R.id.message);

        judul.setText(title);
        pesan.setText(message);

        Glide.with(context)
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
