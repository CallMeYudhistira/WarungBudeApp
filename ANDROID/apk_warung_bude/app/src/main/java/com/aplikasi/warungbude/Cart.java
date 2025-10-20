package com.aplikasi.warungbude;

public class Cart {
    private String cart_id;
    private String product_name;
    private String pict;
    private String category_name;
    private String unit_name;
    private String user_name;
    private int selling_price;
    private int quantity;
    private int subtotal;
    private int stock;

    public Cart(String cart_id, String product_name, String pict, String category_name, String unit_name, String user_name, int selling_price, int quantity, int subtotal, int stock) {
        this.cart_id = cart_id;
        this.product_name = product_name;
        this.pict = pict;
        this.category_name = category_name;
        this.unit_name = unit_name;
        this.user_name = user_name;
        this.selling_price = selling_price;
        this.quantity = quantity;
        this.subtotal = subtotal;
        this.stock = stock;
    }

    public String getCart_id() {
        return cart_id;
    }

    public String getProduct_name() {
        return product_name;
    }

    public String getPict() {
        return pict;
    }

    public String getCategory_name() {
        return category_name;
    }

    public String getUnit_name() {
        return unit_name;
    }

    public String getUser_name() {
        return user_name;
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

    public int getStock() {
        return stock;
    }
}
