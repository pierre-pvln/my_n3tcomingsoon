var BackgroundImage = new Class({

	Implements: [Events, Options],

	options: {
	  'src': null, 
		'alt': '',
		'class': 'background-image'
	},

	initialize: function(options) {
		this.setOptions(options);
		if (this.options['src']) {
  		this.image = new Element('img', {
  			'src': this.options['src'],
  			'alt': this.options['alt'],
  			'class': this.options['class'],
  			'styles': {
          'position': 'absolute'
        }  			
  		}).addEvent('load', this.loaded.bind(this));
  
  		window.addEvents({
  			'resize': this.resize.bind(this)
  		});
		}
	},

	loaded: function() {
		this.wrapper = new Element('div', {
			'class': this.options['class'],
			'styles': {
        'position': 'absolute',
        'overflow': 'hidden',
        'z-index': -1
      }
		}).inject(document.body, 'top');
		this.image.inject(this.wrapper);
		this.imageSize = this.image.getSize();
		this.imageRatio = this.imageSize.x / this.imageSize.y;
		this.resize();
	},

	resize: function() {
    if (this.imageSize) {	
  		var windowSize = window.getSize();
  		var windowRatio = windowSize.x / windowSize.y;  		
  		var styles = {width: 0, height: 0, left: 0, top: 0};
  		
  		if (this.imageRatio < windowRatio) {
  			styles.width  = windowSize.x;
  			styles.height = Math.round(styles.width / this.imageRatio);
  			styles.top = Math.round((windowSize.y - styles.height) / 2);
  		} else if (this.imageRatio > windowRatio) {
  			styles.height = windowSize.y;
  			styles.width  = Math.round(styles.height * this.imageRatio);
  			styles.left = Math.round((windowSize.x - styles.width) / 2);
  		} else {
  			styles.width  = windowSize.x;
  			styles.height = windowSize.y;
  		}
  		
  		this.wrapper.setStyles({width: windowSize.x, height: windowSize.y});
  		this.image.setStyles(styles);
		}
	},
});