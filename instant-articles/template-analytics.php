<!-- Analytics -->
<script>
    var noautoptimize = 1;
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
    ga('create', 'UA-985687-8', 'auto');
    ga('require', 'displayfeatures')
    ga('set', 'anonymizeIp', true);
    ga('set', 'campaignName', 'Instant Articles');
    ga('set', 'campaignSource', 'facebook.com');
    ga('set', 'campaignMedium', 'instant article');
    ga('send', 'pageview', {title: <?php echo json_encode($post->post_title, JSON_UNESCAPED_UNICODE); ?>});
</script>