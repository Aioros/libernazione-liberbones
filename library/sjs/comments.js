(function() {
	var $ = jQuery;

	function buildComment(commentData) {
		var comment = $("#comment_placeholder").clone().show();
		comment.attr("id", "comment-" + commentData.id);
		comment.find(".comment-author .avatar").attr("src", commentData.author_avatar_urls["48"]);
		var authorLink = comment.find(".comment-author .fn");
		if (commentData.author_url !== "")
			authorLink.html($("<a>").attr("href", commentData.author_url).attr("rel", "external nofollow").addClass("url").text(commentData.author_name));
		else
			authorLink.text(commentData.author_name);
		var commentDate = new Date(commentData.date_gmt);
		comment.find("time").attr("datetime", commentDate.toISOString());
		comment.find("time a").attr("href", commentData.link).text(commentDate.toLocaleString('it', {
			year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'
		}).replace(",", " alle"));
		comment.find(".comment_content").html(commentData.content.rendered);
		var commentReply = $("<a>")
			.attr("rel", "nofollow")
			.addClass("comment-reply-link")
			.attr("href", commentData.link.substr(commentData.link.indexOf("#")) + "?replytocom="+commentData.id+"#respond")
			.attr("onclick", 'return addComment.moveForm( "comment-'+commentData.id+'", "'+commentData.id+'", "respond", "'+libComments.post_id+'" )')
			.text("Rispondi");
		commentReply.insertAfter(comment.find(".comment_content"));

		if (libComments.ec) {
			var commentEdit = $("<a>")
				.addClass("comment-edit-link")
				.attr("href", "http://libernazione.it/wp-admin/comment.php?action=editcomment&c=" + libComments.post_id)
				.text("(Modifica)");
			commentEdit.insertAfter(commentReply);
		}

		return comment;
	}

	wp.api.loadPromise.done( function() {
		
		var commentsCollection = new wp.api.collections.Comments();
		var comments = commentsCollection.fetch({
			data: {"post": libComments.post_id}
		}).done(function(comments) {
			$(".commentlist .spinner").hide();
			//console.log(comments);
			
			var comment;
			var parent;
			comments.forEach(function(commentData, i) {
				comment = buildComment(commentData);
				if (commentData.parent === 0) {
					comment.data("depth", 1).addClass("depth-1").appendTo($(".commentlist"));
				} else {
					var parent = $("#comment-" + commentData.parent);
					comment.data("depth", parent.data("depth") + 1).addClass("depth-" + parent.data("depth") + 1).appendTo(parent);
				}
			});
		});

	} );
})();