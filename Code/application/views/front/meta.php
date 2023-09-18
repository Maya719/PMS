<title><?=(isset($page_title) && !empty($page_title))?htmlspecialchars($page_title):'Home'?></title>
<meta name="description" content="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):htmlspecialchars($page_title)?>" />
<meta name="keywords" content="<?=(isset($meta_keywords) && !empty($meta_keywords))?htmlspecialchars($meta_keywords):htmlspecialchars($page_title)?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?=(isset($page_title) && !empty($page_title))?htmlspecialchars($page_title):'Home'?>" />
<meta property="og:description" content="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):htmlspecialchars($page_title)?>" />
<meta property="og:image" itemprop="image" content="<?=base_url('assets/uploads/logos/'.full_logo())?>" />
<link rel="shortcut icon" href="<?=base_url('assets/uploads/logos/'.favicon())?>">