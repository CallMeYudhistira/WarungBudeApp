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
    public static String URLGetProduct = IP + "api/products/list";
    public static String URLGetCart = IP + "api/carts/list";
    public static String URLStoreCart = IP + "api/carts/store";
}
