package com.aplikasi.warungbude;

public class Product {
    private String product_detail_id;
    private String product_name;
    private String pict;
    private String category_name;
    private String unit_name;
    private int purchase_price;
    private int selling_price;
    private int stock;

    public Product(String product_detail_id, String product_name, String pict, String category_name, String unit_name, int purchase_price, int selling_price, int stock) {
        this.product_detail_id = product_detail_id;
        this.product_name = product_name;
        this.pict = pict;
        this.category_name = category_name;
        this.unit_name = unit_name;
        this.purchase_price = purchase_price;
        this.selling_price = selling_price;
        this.stock = stock;
    }

    public String getProduct_detail_id() {
        return product_detail_id;
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

    public int getPurchase_price() {
        return purchase_price;
    }

    public int getSelling_price() {
        return selling_price;
    }

    public int getStock() {
        return stock;
    }
}
