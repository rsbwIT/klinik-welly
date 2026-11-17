using System;
using System.Security.Cryptography;
using System.Text;

public class Program {
    public static string Dec(string b64) {
        byte[] key = Encoding.UTF8.GetBytes("Bar12345Bar12345");
        byte[] iv = Encoding.UTF8.GetBytes("sayangsamakhanza");
        byte[] cipher = Convert.FromBase64String(b64);
        using (Aes aes = Aes.Create()) {
            aes.Key = key;
            aes.IV = iv;
            aes.Mode = CipherMode.CBC;
            aes.Padding = PaddingMode.PKCS7;
            using (var d = aes.CreateDecryptor()) {
                byte[] res = d.TransformFinalBlock(cipher, 0, cipher.Length);
                return Encoding.UTF8.GetString(res);
            }
        }
    }

    public static void Main() {
        Console.WriteLine("USER: " + Dec("O5MeIU3iQcn/BTTzoZmMPg=="));
        Console.WriteLine("PAS: " + Dec("+LJMMWgOdsh3O/vdS99sqWRlzN7o/7Y34jAFEXnSaDQ="));
        Console.WriteLine("HOST: " + Dec("UfjQfR828ZwrcH220jPelA=="));
    }
}
