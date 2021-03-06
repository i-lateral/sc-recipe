<div class="post-summary">
	<h2>
		<a href="$Link" title="<%t SilverStripe\\Blog\\Model\\Blog.ReadMoreAbout "Read more about '{title}'..." title=$Title %>">
			<% if $MenuTitle %>$MenuTitle
			<% else %>$Title<% end_if %>
		</a>
	</h2>

	<p class="post-image">
		<a href="$Link" title="<%t SilverStripe\\Blog\\Model\\Blog.ReadMoreAbout "Read more about '{title}'..." title=$Title %>">
			<% if $FeaturedImage %>
				<img class="img-fluid" src="$FeaturedImage.FocusFill(1080,400).URL" alt="$FeaturedImage.Title">
			<% else %>
				<img class="img-fluid" src="$Parent.FeaturedImage.FocusFill(950,400).URL" alt="$Parent.FeaturedImage.Title">
			<% end_if %>
		</a>
	</p>

	<% include SilverStripe\\Blog\\EntryMeta %>

	<% if $Summary %>
		$Summary
	<% else %>
		<p>$Excerpt</p>
	<% end_if %>
	    <p>
			<a class="btn btn-primary" href="$Link">
				<%t SilverStripe\\Blog\\Model\\Blog.ReadMoreAbout "Read more about '{title}'..." title=$Title %>
			</a>
		</p>

</div>
<hr />
