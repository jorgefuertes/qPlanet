<script type="text/javascript">

//<![CDATA[
	/* Hide and show the content's div from a post */
	function ToggleContent(postId)
	{
		if($("#" + postId + " > .content").attr("style").search("display: none") == -1)
		{
			$("#" + postId + " > .toggle > a").html("&#8659;Read more");
			$("#" + postId + " > .description").fadeTo("slow", 1);
		}
		else
		{
			$("#" + postId + " > .toggle > a").html("&#8657;Read less");
			$("#" + postId + " > .description").fadeTo("slow", 0.33);
		}
		$("#" + postId + " > .content").slideToggle();
	}
//]]>

</script>
