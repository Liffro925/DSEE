 document.addEventListener('DOMContentLoaded', () => {
     const mobileToggle = document.querySelector('.mobile-menu-toggle');
     const nav = document.querySelector('nav');
     const mobileOverlay = document.querySelector('.mobile-overlay');
     
     if (mobileToggle && nav && mobileOverlay) {
         const toggleMenu = () => {
             nav.classList.toggle('active');
             mobileOverlay.classList.toggle('active');
             const isOpen = nav.classList.contains('active');
             const icon = mobileToggle.querySelector('i');
            if (icon) {
                icon.className = isOpen ? 'fa-solid fa-times' : 'fa-solid fa-bars';
            }
            document.body.style.overflow = isOpen ? 'hidden' : '';
         };
         
        mobileToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);
        
        document.querySelectorAll('nav a').forEach(link => {
             link.addEventListener('click', () => {
                 if (nav.classList.contains('active')) {
                     toggleMenu();
                 }
             });
         });
     }
     
     document.querySelectorAll('a[href^="#"]').forEach(anchor => {
         anchor.addEventListener('click', function (e) {
             e.preventDefault();
             document.querySelector(this.getAttribute('href')).scrollIntoView({
                 behavior: 'smooth'
             });
         });
     });

     const slider = document.querySelector('.testimonials-slider');
     const viewport = document.querySelector('.testimonials-viewport');
     if (slider && viewport) {
         const items = Array.from(slider.children);
         const prev = document.querySelector('.testimonials-prev');
         const next = document.querySelector('.testimonials-next');
         const dotsContainer = document.querySelector('.testimonials-dots');
         let index = 0;
         let intervalId;

         const updateWidths = () => {
             const vw = viewport.clientWidth;
             items.forEach(item => {
                 item.style.minWidth = vw + 'px';
                 item.style.flex = '0 0 ' + vw + 'px';
             });
             slider.style.width = (vw * items.length) + 'px';
             goTo(index, false);
         };

         const createDots = () => {
             dotsContainer.innerHTML = '';
             items.forEach((_, i) => {
                 const dot = document.createElement('div');
                 dot.className = 'testimonials-dot';
                 dot.addEventListener('click', () => {
                     index = i;
                     goTo(index);
                     reset();
                 });
                 dotsContainer.appendChild(dot);
             });
             refreshDots();
         };

         const refreshDots = () => {
             const dots = dotsContainer.querySelectorAll('.testimonials-dot');
             dots.forEach((d, i) => d.classList.toggle('active', i === index));
         };

         const goTo = (i, animate = true) => {
             if (i < 0) i = items.length - 1;
             if (i >= items.length) i = 0;
             index = i;
             slider.style.transition = animate ? 'transform 0.5s ease-in-out' : 'none';
             slider.style.transform = `translateX(${-index * viewport.clientWidth}px)`;
             refreshDots();
         };

         const nextSlide = () => goTo(index + 1);
         const prevSlide = () => goTo(index - 1);

         const start = () => {
             intervalId = setInterval(nextSlide, 6000);
         };
         const reset = () => {
             clearInterval(intervalId);
             start();
         };

        createDots();
        updateWidths();
        index = 0;
        slider.style.transition = 'none';
        slider.style.transform = `translateX(0px)`;
        refreshDots();
         start();

         next && next.addEventListener('click', () => { nextSlide(); reset(); });
         prev && prev.addEventListener('click', () => { prevSlide(); reset(); });
         window.addEventListener('resize', updateWidths);
     }
    const addDialog = document.getElementById('addToCartDialog');
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            try {
                const resp = await fetch('cart.php', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'fetch' } });
                if (resp.ok) {
                    if (addDialog && typeof addDialog.showModal === 'function') { addDialog.showModal(); }
                    try {
                        const badge = document.querySelector('.profile-icon .cart-badge');
                        if (badge) { badge.textContent = String((parseInt(badge.textContent||'0',10) || 0) + 1); }
                        else {
                            const cartIcon = document.querySelector('.profile-icon a[href="cart.php"]');
                            if (cartIcon) {
                                const span = document.createElement('span'); span.className='cart-badge'; span.textContent='1'; cartIcon.parentElement.appendChild(span);
                            }
                        }
                    } catch(_) {}
                }
            } catch(_) {}
        });
    });

    const langSelect = document.getElementById('siteLang');
    if (langSelect) {
        const dict = {
            home: { 'zh': '首页', 'zh-CN': '首页', 'zh-TW': '首頁' },
            courses: { 'zh': '我们的课程', 'zh-CN': '我们的课程', 'zh-TW': '我們的課程' },
            tutors: { 'zh': '我们的导师', 'zh-CN': '我们的导师', 'zh-TW': '我們的導師' },
            community: { 'zh': '社群', 'zh-CN': '社群', 'zh-TW': '社群' },
            feedback: { 'zh': '意见反馈', 'zh-CN': '意见反馈', 'zh-TW': '意見回饋' },
            contact: { 'zh': '联系我们', 'zh-CN': '联系我们', 'zh-TW': '聯絡我們' },
            user: { 'zh': '用户', 'zh-CN': '用户', 'zh-TW': '用戶' },
            logout: { 'zh': '登出', 'zh-CN': '登出', 'zh-TW': '登出' },
            hero_title: { 'zh': '欢迎来到DespicableEnglish', 'zh-CN': '欢迎来到DespicableEnglish', 'zh-TW': '歡迎來到DespicableEnglish' },
            explore_courses: { 'zh': '探索课程', 'zh-CN': '探索课程', 'zh-TW': '探索課程' },
            about_us: { 'zh': '关于我们', 'zh-CN': '关于我们', 'zh-TW': '關於我們' },
            about_p1: {
                'zh': '在DespicableEnglish，我们将专业教学与实用框架相结合，让学生每周都能看到进步。我们的导师是考试专家，他们将复杂的技能转化为清晰、可重复的步骤。',
                'zh-CN': '在DespicableEnglish，我们将专业教学与实用框架相结合，让学生每周都能看到进步。我们的导师是考试专家，他们将复杂的技能转化为清晰、可重复的步骤。',
                'zh-TW': '在DespicableEnglish，我們將專業教學與實用框架相結合，讓學生每週都能看到進步。我們的導師是考試專家，他們將複雜的技能轉化為清晰、可重複的步驟。'
            },
            proven_methods: { 'zh': '验证有效的方法', 'zh-CN': '验证有效的方法', 'zh-TW': '驗證有效的方法' },
            proven_p: {
                'zh': '任务模板、分数描述检查表和范例答案帮助学生有目的地练习。',
                'zh-CN': '任务模板、分数描述检查表和范例答案帮助学生有目的地练习。',
                'zh-TW': '任務模板、分數描述檢查表和範例答案幫助學生有目的地練習。'
            },
            personal_feedback: { 'zh': '个人化反馈', 'zh-CN': '个人化反馈', 'zh-TW': '個人化回饋' },
            personal_p: {
                'zh': '每份写作都会获得逐行评论和清晰的改进步骤。',
                'zh-CN': '每份写作都会获得逐行评论和清晰的改进步骤。',
                'zh-TW': '每份寫作都會獲得逐行評論和清晰的改進步驟。'
            },
            weekly_tracking: { 'zh': '每周追踪', 'zh-CN': '每周追踪', 'zh-TW': '每週追蹤' },
            weekly_p: {
                'zh': '简洁的仪表板显示语法、词汇和任务完成度的进步。',
                'zh-CN': '简洁的仪表板显示语法、词汇和任务完成度的进步。',
                'zh-TW': '簡潔的儀表板顯示語法、詞彙和任務完成度的進步。'
            },
            view_courses: { 'zh': '查看课程', 'zh-CN': '查看课程', 'zh-TW': '查看課程' },
            speak_tutor: { 'zh': '咨询导师', 'zh-CN': '咨询导师', 'zh-TW': '諮詢導師' },
            stat_students: { 'zh': '辅导了1,200多名学生', 'zh-CN': '辅导了1,200多名学生', 'zh-TW': '輔導了1,200多名學生' },
            stat_target: { 'zh': '92%达到目标', 'zh-CN': '92%达到目标', 'zh-TW': '92%達到目標' },
            stat_band: { 'zh': '雅思平均7.0+分', 'zh-CN': '雅思平均7.0+分', 'zh-TW': '雅思平均7.0+分' },
            book_now: { 'zh': '立即预订', 'zh-CN': '立即预订', 'zh-TW': '立即預訂' },
            your_cart: { 'zh': '您的购物车', 'zh-CN': '您的购物车', 'zh-TW': '您的購物車' },
            cart_empty: { 'zh': '您的购物车是空的。', 'zh-CN': '您的购物车是空的。', 'zh-TW': '您的購物車是空的。' },
            go_shop: { 'zh': '前往商店', 'zh-CN': '前往商店', 'zh-TW': '前往商店' },
            choose_tutor: { 'zh': '选择导师', 'zh-CN': '选择导师', 'zh-TW': '選擇導師' },
            add_to_cart: { 'zh': '加入购物车', 'zh-CN': '加入购物车', 'zh-TW': '加入購物車' },
            filter: { 'zh': '筛选', 'zh-CN': '筛选', 'zh-TW': '篩選' },
            added_to_cart: { 'zh': '已加入购物车', 'zh-CN': '已加入购物车', 'zh-TW': '已加入購物車' },
            go_to_cart: { 'zh': '前往购物车', 'zh-CN': '前往购物车', 'zh-TW': '前往購物車' },
            go_home: { 'zh': '前往首页', 'zh-CN': '前往首页', 'zh-TW': '前往首頁' },
            keep_buying: { 'zh': '继续选购', 'zh-CN': '继续选购', 'zh-TW': '繼續選購' }
        };

        Object.assign(dict, {
            checkout: { 'zh': '结账', 'zh-CN': '结账', 'zh-TW': '結帳' },
            full_name: { 'zh': '完整姓名', 'zh-CN': '完整姓名', 'zh-TW': '完整姓名' },
            email: { 'zh': '电子邮件', 'zh-CN': '电子邮件', 'zh-TW': '電子郵件' },
            card_number: { 'zh': '卡号', 'zh-CN': '卡号', 'zh-TW': '卡號' },
            mm_yy: { 'zh': '月/年', 'zh-CN': '月/年', 'zh-TW': '月/年' },
            cvc: { 'zh': 'CVC', 'zh-CN': 'CVC', 'zh-TW': 'CVC' },
            pay_now: { 'zh': '立即付款', 'zh-CN': '立即付款', 'zh-TW': '立即付款' },
            tutor_label: { 'zh': '导师：', 'zh-CN': '导师：', 'zh-TW': '導師：' },
            qty: { 'zh': '数量', 'zh-CN': '数量', 'zh-TW': '數量' },
            remove: { 'zh': '移除', 'zh-CN': '移除', 'zh-TW': '移除' },
            subtotal: { 'zh': '小计：', 'zh-CN': '小计：', 'zh-TW': '小計：' },
            update_cart: { 'zh': '更新购物车', 'zh-CN': '更新购物车', 'zh-TW': '更新購物車' },
            remove_all: { 'zh': '移除全部', 'zh-CN': '移除全部', 'zh-TW': '移除全部' },
            proceed_checkout: { 'zh': '前往结账', 'zh-CN': '前往结账', 'zh-TW': '前往結帳' },
            please_login_checkout: { 'zh': '请登录以继续结账。', 'zh-CN': '请登录以继续结账。', 'zh-TW': '請登入以繼續結帳。' },
            log_in: { 'zh': '登录', 'zh-CN': '登录', 'zh-TW': '登入' },
            error_valid_email: { 'zh': '需要有效的电子邮件', 'zh-CN': '需要有效的电子邮件', 'zh-TW': '需要有效的電子郵件' },
            error_card_digits: { 'zh': '卡号必须为13-19位数字', 'zh-CN': '卡号必须为13-19位数字', 'zh-TW': '卡號必須為13-19位數字' },
            error_exp_format: { 'zh': '有效期限必须为月/年（MM/YY）', 'zh-CN': '有效期限必须为月/年（MM/YY）', 'zh-TW': '有效期限必須為月/年（MM/YY）' },
            error_cvc_digits: { 'zh': 'CVC必须为3-4位数字', 'zh-CN': 'CVC必须为3-4位数字', 'zh-TW': 'CVC必須為3-4位數字' },
            error_full_name_required: { 'zh': '完整姓名为必填', 'zh-CN': '完整姓名为必填', 'zh-TW': '完整姓名為必填' }
        });

        dict['product_name_ielts-1on1-60'] = { 'zh': '雅思一对一（60分钟）', 'zh-CN': '雅思一对一（60分钟）', 'zh-TW': '雅思一對一（60分鐘）' };
        dict['product_desc_ielts-1on1-60'] = { 'zh': '口语练习 + 分数描述检查表训练。', 'zh-CN': '口语练习 + 分数描述检查表训练。', 'zh-TW': '口語練習 + 分數描述檢查表訓練。' };
        dict['price_ielts-1on1-60'] = { 'zh': '600 港元', 'zh-CN': '600 港元', 'zh-TW': '600 港元' };

        dict['speak_to_us'] = { 'zh': '联系我们', 'zh-CN': '联系我们', 'zh-TW': '聯繫我們' };
        dict['book_lessons'] = { 'zh': '预订课程', 'zh-CN': '预订课程', 'zh-TW': '預訂課程' };
        dict['product_name_dse-1on1-60'] = { 'zh': 'DSE英文一对一（60分钟）', 'zh-CN': 'DSE英文一对一（60分钟）', 'zh-TW': 'DSE英文一對一（60分鐘）' };
        dict['product_desc_dse-1on1-60'] = { 'zh': '60分钟个性化一对一课程。考试策略和历届试题。', 'zh-CN': '60分钟个性化一对一课程。考试策略和历届试题。', 'zh-TW': '60分鐘個人化一對一課程。考試策略和歷屆試題。' };
        dict['price_dse-1on1-60'] = { 'zh': '550 港元', 'zh-CN': '550 港元', 'zh-TW': '550 港元' };
        dict['product_name_dse-1on1-90'] = { 'zh': 'DSE英文一对一（90分钟）', 'zh-CN': 'DSE英文一对一（90分钟）', 'zh-TW': 'DSE英文一對一（90分鐘）' };
        dict['product_desc_dse-1on1-90'] = { 'zh': '90分钟深入指导，并提供写作反馈。', 'zh-CN': '90分钟深入指导，并提供写作反馈。', 'zh-TW': '90分鐘深入指導，並提供寫作回饋。' };
        dict['price_dse-1on1-90'] = { 'zh': '780 港元', 'zh-CN': '780 港元', 'zh-TW': '780 港元' };
        dict['product_name_junior-1on1-60'] = { 'zh': '青少年英语一对一（60分钟）', 'zh-CN': '青少年英语一对一（60分钟）', 'zh-TW': '青少年英語一對一（60分鐘）' };
        dict['product_desc_junior-1on1-60'] = { 'zh': '互动基础：语法、词汇、自信。', 'zh-CN': '互动基础：语法、词汇、自信。', 'zh-TW': '互動基礎：語法、詞彙、自信。' };
        dict['price_junior-1on1-60'] = { 'zh': '500 港元', 'zh-CN': '500 港元', 'zh-TW': '500 港元' };

        dict['please_login_create_post'] = { 'zh': '请登录以创建帖子。', 'zh-CN': '请登录以创建帖子。', 'zh-TW': '請登入以建立貼文。' };
        dict.t1_text = {
            'zh': '「最好的英语学习中心！导师们真正解释了有效的策略。一个月后，我看到我的写作有所进步，我终于对在课堂上发言感到自信。」',
            'zh-CN': '「最好的英语学习中心！导师们真正解释了有效的策略。一个月后，我看到我的写作有所进步，我终于对在课堂上发言感到自信。」',
            'zh-TW': '「最好的英語學習中心！導師們真正解釋了有效的策略。一個月後，我看到我的寫作有所進步，我終於對在課堂上發言感到自信。」'
        };
        dict.t1_author = { 'zh': '－ A先生', 'zh-CN': '－ A先生', 'zh-TW': '－ A先生' };
        dict.t2_text = {
            'zh': '「多亏了每周的反馈和模拟考试，我进步显著。课程结构清晰，但也很有趣，我总是清楚知道接下来要练习什么。」',
            'zh-CN': '「多亏了每周的反馈和模拟考试，我进步显著。课程结构清晰，但也很有趣，我总是清楚知道接下来要练习什么。」',
            'zh-TW': '「多虧了每週的回饋和模擬考試，我進步顯著。課程結構清晰，但也很有趣，我總是清楚知道接下來要練習什麼。」'
        };
        dict.t2_author = { 'zh': '－ B先生', 'zh-CN': '－ B先生', 'zh-TW': '－ B先生' };
        dict.t3_text = {
            'zh': '「8周后雅思获得7.5分。口语练习和分数描述检查表是游戏规则的改变者——现在我知道如何每次都达到标准。」',
            'zh-CN': '「8周后雅思获得7.5分。口语练习和分数描述检查表是游戏规则的改变者——现在我知道如何每次都达到标准。」',
            'zh-TW': '「8週後雅思獲得7.5分。口語練習和分數描述檢查表是遊戲規則的改變者——現在我知道如何每次都達到標準。」'
        };
        dict.t3_author = { 'zh': '－ C先生', 'zh-CN': '－ C先生', 'zh-TW': '－ C先生' };
        dict.t4_text = {
            'zh': '「我的DSE卷二从第3级跳到第5级。导师的模板和逐行反馈帮助我修正语法并建立更强的论点。」',
            'zh-CN': '「我的DSE卷二从第3级跳到第5级。导师的模板和逐行反馈帮助我修正语法并建立更强的论点。」',
            'zh-TW': '「我的DSE卷二從第3級跳到第5級。導師的模板和逐行回饋幫助我修正語法並建立更強的論點。」'
        };
        dict.t4_author = { 'zh': '－ D先生', 'zh-CN': '－ D先生', 'zh-TW': '－ D先生' };
        dict.t5_text = {
            'zh': '「我的女儿现在喜欢英语了。她读得更多，每周写日记，她的老师说她的自信心增长了很多。」',
            'zh-CN': '「我的女儿现在喜欢英语了。她读得更多，每周写日记，她的老师说她的自信心增长了很多。」',
            'zh-TW': '「我的女兒現在喜歡英語了。她讀得更多，每週寫日記，她的老師說她的自信心增長了很多。」'
        };
        dict.t5_author = { 'zh': '－ E先生', 'zh-CN': '－ E先生', 'zh-TW': '－ E先生' };

        const applyDict = (target) => {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] && dict[key][target]) {
                    el.textContent = dict[key][target];
                } else if (target === 'en') {
                    const original = el.getAttribute('data-i18n-original');
                    if (original) el.textContent = original;
                }
            });
            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (dict[key] && dict[key][target]) {
                    el.setAttribute('placeholder', dict[key][target]);
                } else if (target === 'en') {
                    const original = el.getAttribute('data-i18n-ph-original');
                    if (original) el.setAttribute('placeholder', original);
                }
            });
        };

        document.querySelectorAll('[data-i18n]').forEach(el => {
            el.setAttribute('data-i18n-original', el.textContent.trim());
        });
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const ph = el.getAttribute('placeholder') || '';
            el.setAttribute('data-i18n-ph-original', ph);
        });
        const collectTextNodes = (root) => {
            const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    if (!node.nodeValue) return NodeFilter.FILTER_REJECT;
                    const text = node.nodeValue.trim();
                    if (!text) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentElement;
                    if (!parent || ['SCRIPT','STYLE','NOSCRIPT'].includes(parent.tagName)) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    return NodeFilter.FILTER_ACCEPT;
                }
            });
            const nodes = [];
            let n; while ((n = walker.nextNode())) nodes.push(n);
            return nodes;
        };

        const originalTexts = new WeakMap();
        const nodes = collectTextNodes(document.body);
        nodes.forEach(n => originalTexts.set(n, n.nodeValue));

        const applyTranslations = (translations) => {
            const len = Math.min(translations.length, nodes.length);
            for (let i = 0; i < len; i++) {
                const n = nodes[i];
                const orig = originalTexts.get(n);
                if (!orig) continue;
                const item = translations[i];
                const t = (typeof item === 'string') ? item : (item && item.translatedText) ? item.translatedText : orig;
                n.nodeValue = t;
            }
        };

        const translateAll = async (targetRaw) => {
            const target = (targetRaw === 'zh' || targetRaw === 'zh-CN') ? 'zh' : (targetRaw === 'zh-TW' ? 'zh' : targetRaw);
            if (targetRaw === 'en') {
                nodes.forEach(n => { const orig = originalTexts.get(n); if (orig) n.nodeValue = orig; });
                applyDict('en');
                localStorage.setItem('site_lang', 'en');
                return;
            }
            applyDict(targetRaw);
            try {
                const res = await fetch('i18n/' + (targetRaw === 'zh-TW' ? 'zh-TW' : 'zh-CN') + '.json?_=' + Date.now());
                if (res.ok) {
                    const ext = await res.json();
                    Object.keys(ext).forEach(k => {
                        dict[k] = Object.assign({}, dict[k] || {}, { [targetRaw]: ext[k] });
                    });
                    applyDict(targetRaw);
                }
            } catch (e) { }
            const texts = nodes.map(n => originalTexts.get(n) || '');
            const CHUNK = 150;
            const translated = [];
            for (let i = 0; i < texts.length; i += CHUNK) {
                const batch = texts.slice(i, i + CHUNK);
                try {
                    const resp = await fetch('translate.php', {
                        method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ texts: batch, source: 'en', target })
                    });
                    const data = await resp.json();
                    if (data && Array.isArray(data.translations)) {
                        translated.push(...data.translations);
                    } else if (Array.isArray(data)) {
                        translated.push(...data);
                    } else {
                        translated.push(...batch);
                    }
                } catch (e) {
                    translated.push(...batch);
                }
            }
            applyTranslations(translated);
            localStorage.setItem('site_lang', targetRaw);
        };

        const saved = localStorage.getItem('site_lang');
        if (saved) {
            langSelect.value = saved;
            translateAll(saved);
        }
        langSelect.addEventListener('change', () => translateAll(langSelect.value));
    }
 });
