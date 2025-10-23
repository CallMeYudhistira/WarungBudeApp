package com.aplikasi.warungbude;

public class History {
    private String transaction_id;
    private String payment;
    private String date;
    private String customer_name;
    private String name;
    private int total;
    private int pay;
    private int change;

    public History(String transaction_id, String payment, String date, String customer_name, String name, int total, int pay, int change) {
        this.transaction_id = transaction_id;
        this.payment = payment;
        this.date = date;
        this.customer_name = customer_name;
        this.name = name;
        this.total = total;
        this.pay = pay;
        this.change = change;
    }

    public String getTransaction_id() {
        return transaction_id;
    }

    public String getPayment() {
        return payment;
    }

    public String getDate() {
        return date;
    }

    public String getCustomer_name() {
        return customer_name;
    }

    public String getName() {
        return name;
    }

    public int getTotal() {
        return total;
    }

    public int getPay() {
        return pay;
    }

    public int getChange() {
        return change;
    }
}
