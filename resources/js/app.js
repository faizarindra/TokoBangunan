import "./bootstrap";

const CryptoJS = require("../../node_modules/crypto-js");

window.CryptoJS = CryptoJS;

// window.decryptData = (encrypted) => {
//     let key = process.env.MIX_APP_KEY.substr(7);
//     var encrypted_json = JSON.parse(atob(encrypted));
//     return CryptoJS.AES.decrypt(
//         encrypted_json.value,
//         CryptoJS.enc.Base64.parse(key),
//         {
//             iv: CryptoJS.enc.Base64.parse(encrypted_json.iv),
//         }
//     ).toString(CryptoJS.enc.Utf8);
// };

// window.encryptData = (data) => {
//     console.log(data);
//     let key = process.env.MIX_APP_KEY.substr(7);
//     console.log(key);
//     let iv = CryptoJS.lib.WordArray.random(16);
//     // key = CryptoJS.enc.Utf8.parse(this.key);
//     let options = {
//         iv: iv,
//         mode: CryptoJS.mode.CBC,
//         padding: CryptoJS.pad.Pkcs7,
//     };
//     let encrypted = CryptoJS.AES.encrypt(data, key, options);
//     encrypted = encrypted.toString();
//     iv = CryptoJS.enc.Base64.stringify(iv);
//     let result = {
//         iv: iv,
//         value: encrypted,
//         mac: CryptoJS.HmacSHA256(iv + encrypted, key).toString(),
//     };
//     result = JSON.stringify(result);
//     result = CryptoJS.enc.Utf8.parse(result);
//     return CryptoJS.enc.Base64.stringify(result);
// };
