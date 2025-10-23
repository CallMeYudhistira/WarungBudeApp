package com.aplikasi.warungbude;

public class Invoice {
    private String product_name;
    private int selling_price;
    private int quantity;
    private int subtotal;

    public Invoice(String product_name, int selling_price, int quantity, int subtotal) {
        this.product_name = product_name;
        this.selling_price = selling_price;
        this.quantity = quantity;
        this.subtotal = subtotal;
    }

    public String getProduct_name() {
        return product_name;
    }

    public int getSelling_price() {
        return selling_price;
    }

    public int getQuantity() {
        return quantity;
    }

    public int getSubtotal() {
        return subtotal;
    }
}
