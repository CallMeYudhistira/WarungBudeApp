package com.aplikasi.warungbude;

public class URL {
    public static String IP = "http://10.158.181.197:8000/";

    // Authentication
    public static String URLLogin = IP + "api/login";
    public static String URLRegist = IP + "api/register";
    public static String URLLogout = IP + "api/logout";

    // Dashboard
    public static String URLDashboard = IP + "api/dashboard";

    // Image
    public static String URLImage = IP + "images/";

    // Transaction
    public static String URLGetProduct = IP + "api/products/list?product_name=";
    public static String URLGetCart = IP + "api/carts/list";
    public static String URLStoreCart = IP + "api/carts/store";
    public static String URLAddQTYCart = IP + "api/carts/plus";
    public static String URLMinQTYCart = IP + "api/carts/min";
    public static String URLDeleteCart = IP + "api/carts/delete";
    public static String URLTransactionStore = IP + "api/transaction/store";
    public static String URLTransactionDetail = IP + "api/transaction/detail";
}
