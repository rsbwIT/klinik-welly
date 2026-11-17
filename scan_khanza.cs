using System;
using System.IO;
using System.Security.Cryptography;
using System.Text;
using System.Text.RegularExpressions;

public class Program {
    public static string Dec(string b64) {
        try {
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
        } catch (Exception ex) {
            return "ERR: " + ex.Message;
        }
    }

    public static void Main() {
        string[] dirs = Directory.GetDirectories(@"C:\", "*khanza*", SearchOption.TopDirectoryOnly);
        foreach (var d in dirs) {
            string xmlPath = Path.Combine(d, "setting", "database.xml");
            if (!File.Exists(xmlPath)) {
                // check 1 level deeper
                var sub = Directory.GetFiles(d, "database.xml", SearchOption.AllDirectories);
                if (sub.Length > 0) xmlPath = sub[0];
            }
            if (File.Exists(xmlPath)) {
                string text = File.ReadAllText(xmlPath);
                string host = MatchVal(text, "HOST");
                string user = MatchVal(text, "USER");
                string pas = MatchVal(text, "PAS");
                Console.WriteLine("PATH: " + xmlPath);
                Console.WriteLine("  HOST: " + Dec(host));
                Console.WriteLine("  USER: " + Dec(user));
                Console.WriteLine("  PAS: " + Dec(pas));
            }
        }
    }

    private static string MatchVal(string txt, string key) {
        var m = Regex.Match(txt, "<entry key=\"" + key + "\">(.*?)</entry>");
        return m.Success ? m.Groups[1].Value : "";
    }
}
