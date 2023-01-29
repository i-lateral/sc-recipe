<div class="container typography">
    <% if $MapEmbedCode %>
        <div class="map-row row">
            <div class="mb-4 embed-responsive embed-responsive-map">
                $MapEmbedCode
            </div>
        </div>
    <% else_if $FeaturedImage.exists %>
        <div class="banner-image mb-4 d-block">
            $FeaturedImage.FullWidthBanner
        </div>
    <% else_if $FeaturedImagesFromHierachy.exists %>
        <div class="banner-image mb-4 d-block">
            $FeaturedImagesFromHierachy.First.FullWidthBanner
        </div>
    <% end_if %>

    <div class="row">
        <div class="col">
            <h1<% if not $Level(2) %> class="text-center"<% end_if %>>
                $Title
            </h1>
        </div>
        <% include BreadCrumbs %>
    </div>
</div>