(function() {
	var $ = jQuery;

	function buildComment(commentData) {
		var comment = $("#comment_placeholder").clone().show();
		comment.attr("id", "comment-" + commentData.id);
		comment.data("parent", commentData.parent);
		comment.data("paragraph", commentData.paragraph);
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

	function insertComment(commentElement) {
		var parentId = commentElement.data("parent");
		if (parentId === 0) {
			commentElement.data("depth", 1).addClass("depth-1").appendTo($(".commentlist"));
		} else {
			var parent = $("#comment-" + parentId);
			commentElement.data("depth", parseInt(parent.data("depth")) + 1).addClass("depth-" + (parseInt(parent.data("depth")) + 1)).appendTo(parent);
		}
	}

	function pollComments(commentsCollection) {
		var comments = commentsCollection.fetch({ data: {
			post: libComments.post_id,
			after: libComments.lastUpdate
		}}).done(function(comments) {
			//console.log(comments);
			comments.forEach(function(commentData, i) {
				if ($("#comment-" + commentData.id).length === 0) {
					comment = buildComment(commentData);
					insertComment(comment);
				}
			});
		}).always(function() {
			libComments.lastUpdate = (new Date()).toISOString();
			setTimeout(function() { pollComments(commentsCollection); }, 15000);
		});
	}

	function addParagraphComments(paragraph) {
		var comments = $("<span>")
							.addClass("comments")
							.text(paragraph.data("comments") ? paragraph.data("comments") : "+");
		comments.click(function() {
			$(".entry-content").css("right", "150px");
			var inlineComment = $(".commentlist .comment").filter(function() {
				return $(this).data("paragraph") == $(".entry-content p").index(paragraph) && $(this).data("depth") === 1;
			}).clone().addClass("inline-comment");
			$(".entry-content").addClass("comments-active").append(inlineComment);
			inlineComment.offset({top: paragraph.offset().top});
			$(".entry-content .comments").hide();
		});
		paragraph.append(comments);
	}

	// Aggiungiamo uno spinner accanto al tasto commenta
	$("#submit").after($("<span>")
		.addClass("status")
		.append($("<img>").attr("src", "/wp-includes/images/spinner.gif").css({marginLeft: "20px", display: "none"}))
	);

	wp.api.loadPromise.done( function() {
		
		// Load comments async
		var commentsCollection = new wp.api.collections.Comments();
		var comments = commentsCollection.fetch({
			data: {post: libComments.post_id/*, status: "all"*/} // Dipende da cosa facciamo con la moderazione
		}).done(function(comments) {
			$(".commentlist .spinner").hide();
			//console.log(comments);

			var paragraphs = $(".entry-content p").data("comments", 0);
			
			var comment;
			var parent;

			comments.forEach(function(commentData, i) {
				if (commentData.paragraph !== "") {
					var paragraph = paragraphs.eq(commentData.paragraph);
					paragraph.data("comments", paragraph.data("comments") + 1);
				}
				comment = buildComment(commentData);
				insertComment(comment);
			});

			paragraphs.each(function(i) {
				addParagraphComments($(this));
				$(this).hover(function() {
					if (!$(".entry-content").hasClass("comments-active")) {
						clearTimeout(libComments.inlineTimeout);
						paragraphs.find(".comments").hide();
						$(this).find(".comments").show();
					}
				}, function() {
					if (!$(".entry-content").hasClass("comments-active")) {
						var self = this;
						libComments.inlineTimeout = setTimeout(function() { $(self).find(".comments").hide(); }, 1000);
					}
				});
			});

			if (window.location.hash && window.location.hash.match(/^#comment-\d+$/)) {
				$(window.location.hash).get(0).scrollIntoView();
			}

			// Start comment polling
			libComments.lastUpdate = (new Date()).toISOString();
			setTimeout(function() { pollComments(commentsCollection); }, 15000);
		});

		// Hijack comment form for async post
		var commentForm = $("#commentform");
		commentForm.submit(function() {
			var formData = commentForm.serializeArray().reduce(function(obj, item) {
			    obj[item.name] = item.value;
			    return obj;
			}, {});
			//console.log(formData);

			$("#comment").prop("disabled", true);
			$("#submit").prop("disabled", true);
			$("#commentform .status img").show();

			var comment = new wp.api.models.Comment({
				content: formData.comment,
				author_name: formData.author,
				author_email: formData.email,
				author_url: formData.url,
				post: formData.comment_post_ID,
				parent: formData.comment_parent
			});
			comment.save()
				.done(function(response) {
					//console.log("Success", response);
					var comment = buildComment(response);
					if (response.status != "approved") {
						comment.addClass("hold");
						comment.find(".fn").after($("<span>").text(" (Commento in moderazione)"));
					}
					$("#cancel-comment-reply-link").click();
					$("#comment").val("");
					insertComment(comment);
					comment.get(0).scrollIntoView();
				})
				.fail(function(response) {
					//console.log("Failed", response);
					$("#commentform .status").append($("<span>").text("Non è stato possibile inviare il commento"));
				})
				.always(function() {
					$("#comment").prop("disabled", false);
					$("#submit").prop("disabled", false);
					$("#commentform .status img").hide();
				});
			
			return false;
		});
	
	} );
})();