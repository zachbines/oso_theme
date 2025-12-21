<script type="text/template" id="video_template">
	<% var title = name.split(" | "); %>
	<% var client = title.shift(); %>
	<% var v_id = uri.split("/").pop(); %>
    <div style="background-image:url(<%= pictures.sizes[3].link %>)" data-vid="<%= v_id %>">
        
	</div>
    <a href="/index.php/work/?id=<%= v_id %><%= window.location.hash %>">  
			<%= client %>
			<em><%= title.join(" | ") %></em>
    </a>

</script>

<script type="text/template" id="single_video_template">
	<div id="v_modal" class="modalvideo" data-vimeo-id="<%= v_id %>" data-vimeo-autoplay="true">
	</div>
</script>

<script type="text/template" id="radio_template">
	<% var titlesplit = title.split(" - "); %>
	<% var client = titlesplit.shift(); %>
	<a href="/index.php/work/?id=<%= guid %><%= window.location.hash %>">  
			<%= client %>
			<em><%= titlesplit.join(" | ") %></em>
    </a>
</script>