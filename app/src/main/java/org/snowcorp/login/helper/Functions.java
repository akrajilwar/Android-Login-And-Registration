package org.snowcorp.login.helper;

import android.app.Activity;
import android.content.Context;
import android.view.inputmethod.InputMethodManager;

/**
 * Created by Akshay Raj on 06-02-2017.
 * akshay@snowcorp.org
 * www.snowcorp.org
 */

public class Functions {

    //Main URL
    private static String MAIN_URL = "http://192.168.43.166/~snow/android_login/";

    // Login URL
    public static String LOGIN_URL = MAIN_URL + "login.php";

    // Register URL
    public static String REGISTER_URL = MAIN_URL + "register.php";

    // OTP Verification
    public static String OTP_VERIFY_URL = MAIN_URL + "verification.php";

    // Forgot Password
    public static String RESET_PASS_URL = MAIN_URL + "reset-password.php";


    /**
     * Function to logout user
     * Resets the temporary data stored in SQLite Database
     * */
    public boolean logoutUser(Context context){
        DatabaseHandler db = new DatabaseHandler(context);
        db.resetTables();
        return true;
    }

    /**
     *  Email Address Validation
     */
    public static boolean isValidEmailAddress(String email) {
        String ePattern = "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$";
        java.util.regex.Pattern p = java.util.regex.Pattern.compile(ePattern);
        java.util.regex.Matcher m = p.matcher(email);
        return m.matches();
    }

    /**
     *  Hide Keyboard
     */
    public static void hideSoftKeyboard(Activity activity) {
        InputMethodManager inputMethodManager =
                (InputMethodManager) activity.getSystemService(
                        Activity.INPUT_METHOD_SERVICE);
        inputMethodManager.hideSoftInputFromWindow(
                activity.getCurrentFocus().getWindowToken(), 0);
    }
}
