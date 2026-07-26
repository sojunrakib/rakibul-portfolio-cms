INSERT INTO admins (name, email, password_hash, is_active)
VALUES ('Rakibul Hasan', 'admin@example.com', '$2y$10$ZjEKYw2Z.zH.VK5Jrla62e8Tjsij9Kh5PYrVc3ji7sZGcEh4MvIr.', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO hero_content (id, name, designations, location_tag, intro, primary_cta_label, primary_cta_target, secondary_cta_label, secondary_cta_target)
VALUES (1, 'Rakibul Hasan', 'Full-Stack Developer
AI & ML Enthusiast
PHP & MySQL Engineer
Freelance Web Developer', 'Dhaka, Bangladesh', '4th-year Computer Science & Engineering student at ULAB, currently building production e-commerce systems in raw PHP & MySQL at Hexazn — with a growing specialization in AI/ML.', 'View Projects', '#portfolio', 'Download Resume', '/resume')
ON DUPLICATE KEY UPDATE name = VALUES(name), designations = VALUES(designations), location_tag = VALUES(location_tag), intro = VALUES(intro);

INSERT INTO about_content (id, summary, stats_json)
VALUES (1, 'Results-driven Computer Science & Engineering student (4th Year) at the University of Liberal Arts Bangladesh, currently interning as a Full-Stack Web Developer at Hexazn — building a production E-Commerce website from scratch using Raw PHP, MySQL, and live cPanel server deployment. Skilled in designing custom theme-based CMS systems, database architecture, and end-to-end server management. Additional expertise in digital marketing (SEO) and AI/ML (Python, TensorFlow). Proven ability to lead projects, deliver client-ready solutions, and apply Agile methodologies. Passionate about building scalable software products and continuously expanding technical knowledge.', '[{"value":"3+","label":"Years Hands-on Experience"},{"value":"2","label":"Major Projects Shipped"},{"value":"5","label":"Skill Domains"},{"value":"4th","label":"Year CSE Student"}]')
ON DUPLICATE KEY UPDATE summary = VALUES(summary), stats_json = VALUES(stats_json);

INSERT INTO skills (category, name, proficiency, display_order) VALUES
('Languages','Python',88,1),('Languages','PHP',90,2),('Languages','JavaScript',84,3),('Languages','SQL',88,4),('Languages','Java',78,5),('Languages','C',75,6),
('Web Development','HTML',92,1),('Web Development','CSS',90,2),('Web Development','Bootstrap',86,3),('Web Development','Laravel',78,4),('Web Development','REST APIs',82,5),('Web Development','MySQL',88,6),
('AI/ML & Data Science','TensorFlow',82,1),('AI/ML & Data Science','PyTorch',76,2),('AI/ML & Data Science','XGBoost',78,3),('AI/ML & Data Science','Pandas',86,4),('AI/ML & Data Science','NumPy',85,5),('AI/ML & Data Science','Data Analysis',84,6),('AI/ML & Data Science','Data Preprocessing',84,7),('AI/ML & Data Science','Feature Engineering',80,8),('AI/ML & Data Science','Time Series Forecasting',76,9),('AI/ML & Data Science','Deep Learning',78,10),
('Tools','Git',86,1),('Tools','GitHub',86,2),('Tools','Docker',75,3),('Tools','Linux',80,4),('Tools','Google Colab',84,5),('Tools','MLflow',72,6),('Tools','VS Code',92,7),
('Other','Agile',82,1),('Other','SDLC',84,2),('Other','OOP',86,3),('Other','MVC',88,4),('Other','Canva',78,5),('Other','Microsoft Office',82,6);

INSERT INTO experience (id, title, company, location, period, description, display_order) VALUES
(1, 'Full-Stack Web Developer Intern', 'Hexazn', 'Dhaka, Bangladesh', '2026–Present', 'Developed and maintained a production-grade e-commerce platform using raw PHP, MySQL, HTML, CSS, and JavaScript
Built a custom CMS-style, theme-based architecture enabling dynamic management of layouts, products, site settings, and content through an admin dashboard
Designed normalized MySQL databases and optimized SQL queries for products, categories, users, orders, and site configuration
Implemented core e-commerce functionality: product catalog, shopping cart, order management, admin panel, dynamic theme switching
Managed end-to-end cPanel deployment: FTP uploads, database configuration, .htaccess setup, debugging, session management, web security best practices', 1),
(2, 'Full-Stack Web Developer', 'Self-Employed / Freelance (Ostad Platform Graduate)', '', '2025–2026', 'Built and maintained responsive websites and web applications using HTML, CSS, JavaScript, and PHP
Designed and integrated MySQL database schemas and RESTful APIs to deliver seamless user experiences
Collaborated directly with clients to gather requirements, deliver tailored solutions, and ensure on-time delivery', 2),
(3, 'Digital Marketing & SEO Specialist', 'Freelance Consultant (WIT Institute Graduate)', '', '2024–2025', 'Executed SEO strategies including keyword research, on-page and off-page optimization to improve organic traffic
Managed social media marketing campaigns to increase brand visibility and audience engagement
Provided digital marketing consultation to enhance online presence and maximize ROI', 3),
(4, 'AI & Machine Learning Developer', 'Self-Learning', '', '2023–Present', 'Developed and deployed AI-based applications using Python and TensorFlow
Performed data preprocessing, feature engineering, model training, and performance evaluation
Collaborated with peers to implement ML solutions for real-world problems; contributed to research reports and presentations', 4)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description);

INSERT INTO education (id, degree, institution, location, result, period, display_order) VALUES
(1, 'B.Sc. in Computer Science & Engineering', 'University of Liberal Arts Bangladesh (ULAB)', 'Dhaka', 'CGPA 3.48/4.00', '4th Year, ongoing', 1),
(2, 'Higher Secondary Certificate (HSC-2020)', 'Birshrestha Noor Mohammad Public College', '', 'GPA 5.00/5.00', '', 2),
(3, 'Secondary School Certificate (SSC-2018)', 'Kasba Poura High School', '', 'GPA 5.00/5.00', '', 3)
ON DUPLICATE KEY UPDATE degree = VALUES(degree), institution = VALUES(institution), result = VALUES(result);

INSERT INTO projects (id, title, slug, role, description, features, github_url, is_featured, display_order) VALUES
(1, 'Smart Flat Rental Management System', 'smart-flat-rental-management-system', 'Project Lead & Full-Stack Developer', 'Centralised web platform to streamline flat rental in Dhaka & Chattogram, eliminating broker dependency', 'tenant portal, landlord dashboard, real-time notifications, digital rental agreements, Google Maps–integrated search
Review & rating system plus verified listings and secure online payment to build user trust
Built using Agile SDLC — sprint planning, iterative delivery, retrospective reviews', '[ADD GITHUB REPO URL]', 1, 1),
(2, 'Nutrition Planner App', 'nutrition-planner-app', 'Developer', 'Modular Java desktop app for personalised nutrition management using OOP design patterns', 'User Profile Management, Food Diary Logging, Nutrient & Water Intake Tracking, Progress Tracking, Allergen Alerts, Admin Panel
Reminder system to prompt users for timely meals and hydration', '[ADD GITHUB REPO URL]', 1, 2)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), features = VALUES(features);

INSERT INTO project_technologies (project_id, technology) VALUES
(1,'PHP'),(1,'Bootstrap'),(1,'MySQL'),(1,'Google Maps API'),(1,'Agile SDLC'),
(2,'Java'),(2,'Java GUI'),(2,'Object-Oriented Programming (OOP)');

INSERT INTO tech_stack (name, display_order) VALUES
('Python',1),('PHP',2),('JavaScript',3),('MySQL',4),('Laravel',5),('Bootstrap',6),('TensorFlow',7),('PyTorch',8),('Docker',9),('Git',10),('Linux',11),('VS Code',12);

INSERT INTO testimonials (id, name, role, message, is_placeholder, display_order)
VALUES (1, 'Preview testimonial placeholder', 'Editable admin preview', 'This clearly labeled placeholder exists only so Rakibul can preview the testimonial layout before adding real client feedback.', 1, 1)
ON DUPLICATE KEY UPDATE message = VALUES(message), is_placeholder = 1;

INSERT INTO faqs (question, answer, display_order) VALUES
('What technologies do you work with?', 'The portfolio highlights work across PHP, MySQL, JavaScript, Python, AI/ML tooling, and responsive web interfaces. Specific tools can be updated from the admin panel.', 1),
('Are you available for freelance work?', 'Availability can change by semester, internship workload, and project scope. Use the contact form to start a conversation with details.', 2),
('Can I download the resume?', 'Yes. The resume button serves the PDF uploaded from the admin panel. If no resume is uploaded yet, the admin can add it from Website Settings.', 3),
('Can this portfolio content be updated?', 'Yes. Hero, about, skills, projects, experience, education, social links, SEO, blog posts, media, and contact messages are managed through the secure admin panel.', 4);

INSERT INTO social_links (platform, label, url, icon, display_order) VALUES
('Email', 'rakibulhasansojuncse@gmail.com', 'rakibulhasansojuncse@gmail.com', 'mail', 1),
('LinkedIn', 'https://www.linkedin.com/in/rakibul-hasan20', 'https://www.linkedin.com/in/rakibul-hasan20', 'linkedin', 2),
('GitHub', 'https://github.com/sojunrakib', 'https://github.com/sojunrakib', 'github', 3);

INSERT INTO seo_meta (page, title, description, twitter_card) VALUES
('home', 'Rakibul Hasan — Full-Stack Developer & AI/ML Enthusiast', 'Portfolio of Rakibul Hasan, a Full-Stack Developer and AI/ML enthusiast building production PHP, MySQL, and intelligent web systems in Dhaka, Bangladesh.', 'summary_large_image');

INSERT INTO settings (setting_key, setting_value, setting_type, group_name, is_public) VALUES
('site_name', 'Rakibul Hasan', 'text', 'identity', 1),
('logo_text', 'RH', 'text', 'identity', 1),
('theme_color', '#4ee1a0', 'color', 'appearance', 1),
('maintenance_mode', '0', 'boolean', 'system', 0),
('resume_pdf', '', 'file', 'resume', 1),
('phone', '+8801789834538', 'text', 'contact', 1),
('location', '106/7A, Moniporipara, Dhaka, Bangladesh', 'text', 'contact', 1),
('email', 'rakibulhasansojuncse@gmail.com', 'text', 'contact', 1),
('linkedin', 'https://www.linkedin.com/in/rakibul-hasan20', 'text', 'contact', 1),
('github', 'https://github.com/sojunrakib', 'text', 'contact', 1)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type), group_name = VALUES(group_name);
