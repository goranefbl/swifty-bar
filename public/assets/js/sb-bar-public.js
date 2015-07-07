(function( $ ) {
	'use strict';

	// Share Icons
    $.fn.socShare = function(opts) {
    	var $this = this;
    	var $win = $(window);
    	
    	opts = $.extend({
    		attr : 'href',
    		facebook : false,
    		google_plus : false,
    		twitter : false,
    		linked_in : false,
    		pinterest : false
    	}, opts);
    	
    	for(var opt in opts) {
    		
    		if(opts[opt] === false) {
    			continue;
    		}
    		
    		switch (opt) {
    			case 'facebook':
    				var url = 'https://www.facebook.com/sharer/sharer.php?u=';
    				var name = 'Facebook';
    				_popup(url, name, opts[opt], 400, 640);
    				break;
    			
    			case 'twitter':
                    var posttitle = $(".sbtwitter a").data("title");
                    var url = 'https://twitter.com/intent/tweet?text='+posttitle+'&url=';
    				var name = 'Twitter';
    				_popup(url, name, opts[opt], 440, 600);
    				break;
    			
				case 'google_plus':
    				var url = 'https://plus.google.com/share?url=';
    				var name = 'Google+';
    				_popup(url, name, opts[opt], 600, 600);
    				break;
    			
    			case 'linked_in':
    				var url = 'https://www.linkedin.com/shareArticle?mini=true&url=';
    				var name = 'LinkedIn';
    				_popup(url, name, opts[opt], 570, 520);
    				break;
				
				case 'pinterest':
    				var url = 'https://www.pinterest.com/pin/find/?url=';
    				var name = 'Pinterest';
    				_popup(url, name, opts[opt], 500, 800);
    				break;
				default:
					break;
    		}
    	}
		
		function isUrl(data) {
            var regexp = new RegExp( '(^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|(www\\.)?))[\\w-]+(\\.[\\w-]+)+([\\w-.,@?^=%&:/~+#-]*[\\w@?^=%&;/~+#-])?', 'gim' );
            return regexp.test(data);
        }
    	
    	function _popup(url, name, opt, height, width) {
    		if(opt !== false && $this.find(opt).length) {				
				$this.on('click', opt, function(e){
					e.preventDefault();
					
					var top = (screen.height/2) - height/2;
					var left = (screen.width/2) - width/2;
					var share_link = $(this).attr(opts.attr);
					
					if(!isUrl(share_link)) {
						share_link = window.location.href;
					}
					
					window.open(
						url+encodeURIComponent(share_link),
						name,
						'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height='+height+',width='+width+',top='+top+',left='+left
					);
					
					return false;
				});
			}
    	}
    	return;
	};


	$(document).ready(function() {

		$('.sb_share').socShare({
			facebook : '.sbsoc-fb',
			twitter : '.sbsoc-tw',
			google_plus : '.sbsoc-gplus',
			linked_in : '.sbsoc-linked',
			pinterest : '.sbsoc-pint'
		});
		
        // Define Variables
        var ttr_start = $(".ttr_start"),
            ttr_end = $(".ttr_end"),
            docOffset = ttr_start.offset().top,
        	docEndOffset = ttr_end.offset().top,
            elmHeight = docEndOffset - docOffset,
            progressBar = $('.sbprogress-bar'),
            winHeight = $(window).height(),
            docScroll,viewedPortion;

        //Recalculate offsets after images are loaded
        $(window).load(function(){
            docOffset = ttr_start.offset().top,
            docEndOffset = ttr_end.offset().top,
            elmHeight = docEndOffset - docOffset;
        });

        // On Scroll
        $(window).on('scroll', function() {

			docScroll = $(window).scrollTop(),
            viewedPortion = winHeight + docScroll - docOffset;

			if(viewedPortion < 0) { 
				viewedPortion = 0; 
			}
            if(viewedPortion > elmHeight) { 
            	viewedPortion = elmHeight;  
            }
            // viewed percentage
            var viewedPercentage = (viewedPortion / elmHeight) * 100;
			progressBar.css({ width: viewedPercentage + '%' });

		});

		// On Resize
		$(window).on('resize', function() {
			docOffset = ttr_start.offset().top;
			docEndOffset = ttr_end.offset().top;
			elmHeight = docEndOffset - docOffset;
			winHeight = $(window).height();
			$(window).trigger('scroll');
		});

		$(".sb_prev-next-posts a").on('mouseenter touchstart', function(){
			$(this).next('div').css("top","-170px");
		}).on('mouseleave touchend', function(){
			$(this).next('div').css("top","46px");
		});

        //Smooth Scroll
        $('.sb_comment').click(function() {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
               if (target.length) {
                 $('html,body').animate({
                     scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        });

	});

})( jQuery );
