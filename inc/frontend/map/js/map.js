jQuery(document).ready(function ($) {    
    function get_jrrnys(placeId){        
        $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'place_posts',
                    id: placeId
                },
                success: function (response) {
                    $('#place-modal .modal-title').html(response.title);
                    $('#place-modal .place-posts').empty();
                    $('#place-modal .place-posts').append('<div class="col-xs-12 place-text">Places</div>');
                    $(response.posts).each(function (index) {
                        var html = "<div class='col-sm-6 col-md-3 item'>";
                        html += '<div class="map-pop-item">';
                        html += '<a class="map-pop-link" title="' + this.title + '" onclick="trackOutboundLink("' + this.url + '"); return false;" href="' + this.url + '"></a>';
                        html += '<div class="map-pop-thumbnail">';
                        html += '<img alt="' + this.title + '" src="' + this.img + '">';
                        html += '</div>';
                        html += '<div class="map-pop-content">';
                        html += '<div class="title"><span>' + this.title + '</span></div>';
                        //html += '<div class="entry-info post-entry-info author-name">';
                        //html += '<span class="meta-item meta-item-author subtle-text-color stylized-meta"> by: <span class="author vcard"><a class="fn" href="' + this.authorurl + '">' + this.author + ' </a></span>';
                        //html += '</span>';
                        //html += '</div>';
                        
                        html += '</div>';
                        html += '</div>';
                        html += "</div>";
                        $('#place-modal .place-posts').append(html);
                    });
                    $('#place-modal').modal();
                }
        });        
    };
    if($('.map').length){
        var qmap3 = $('.map')
        .gmap3({
            center: [48.8620722, 2.352047],
            zoom: 3,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        });
        qmap3.cluster({
        size: 100,
        markers: jrrny_map_addresses,
        cb: function (markers) {
          if (markers.length > 1) { // 1 marker stay unchanged (because cb returns nothing)
            if (markers.length < 120) {
              return {
                content: "<div class='cluster cluster-1'>" + markers.length + "</div>",
                x: 0,
                y: 0
              };
            }
          }
        }
      });
      qmap3.on({
        click: function (marker, cluster, event) {
            if(marker){
                if(marker.placeId){
                    place_ID = marker.placeId;  
                }
                else{
                    place_ID = placeId;          
                }
                get_jrrnys(place_ID);
            }
            else if(cluster){
                var map = cluster.overlay.map;
                var center = cluster.overlay.getPosition();
                map.setCenter(center);
                map.setZoom(map.getZoom()+1);
            }
        }
      });
    }
    if($('#small-map').length){
        $('#small-map').gmap3({
                center: smallMapCenter,
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.TERRAIN,
                mapTypeControl: false,
                navigationControl: false,
                scrollwheel: false,
                streetViewControl: false,
                draggable: false,
                zoomControl: false
        })
        .marker({
            position: smallMapCenter,
            icon: icon
        })
        .on({
            click: function (marker, event) {
                get_jrrnys(placeId);
            }
        });
    }
});