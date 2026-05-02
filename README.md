# Vortexa-Sentinel-AI-Powered-WAF-
# Vortexa Sentinel (AI-Powered WAF) 🛡️🤖

نظام حماية ذكي لمواقع PHP يعتمد على الأنماط التقليدية والذكاء الاصطناعي لاكتشاف التهديدات.

## 🚀 المميزات
- **AI-Detection**: استخدام Llama 3 لاكتشاف الهجمات غير التقليدية.
- **Telegram Alerts**: تنبيهات فورية تصلك على هاتفك عند أي محاولة اختراق.
- **Real-time Blocking**: حظر المهاجم فوراً ومنعه من الوصول.
- **Security Dashboard**: لوحة تحكم لمراجعة سجلات الهجمات.

## 🛠️ طريقة التشغيل
1. قم بإنشاء Bot على تليجرام واحصل على الـ `Token` والـ `Chat ID`.
2. احصل على API Key من [Groq Cloud](https://console.groq.com/).
3. ضع المفاتيح في ملف `config.php`.
4. أضف الكود التالي في أول سطر في موقعك:
   ```php
   <?php include 'sentinel.php'; ?>
   
