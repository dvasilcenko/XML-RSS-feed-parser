{% for item in feeds %}
<div style="margin: 20px;">
    <a href="index.php?page=category&name={{ item.category }}">{{ item.category }}</a>
</div>
{% endfor %}