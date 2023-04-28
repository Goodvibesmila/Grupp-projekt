// External Dependencies
import React, { Component } from 'react';
import $ from 'jquery';

// Internal Dependencies
import './style.css';

class WplpDivi extends Component {

  static slug = 'wplp_divi';

  constructor(props) {
    super(props);
    this.state = {
      html: '',
      loading: true,
      settings: ''
    };
    this.wrap = React.createRef();
  }

  componentWillMount() {
    if (parseInt(this.props.news_widget_id) !== 0) {
      this.loadHtml(this.props);
    }
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.news_widget_id !== nextProps.news_widget_id) {
      this.loadHtml(nextProps);
    }
  }

  loadHtml(props) {
    if (!this.state.loading) {
      this.setState({
        loading: true
      });
    }

    let datas = {
      id: props.news_widget_id
    };

    fetch(window.et_fb_options.ajaxurl + `?action=wplp_load_html&divi=true&id=` + props.news_widget_id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datas)
    })
    .then(res => res.json())
    .then(
      (result) => {
        this.setState({
          html: result.html,
          loading: false,
          settings: result.settings
        });
      },
      // errors
      (error) => {
        this.setState({
          html: '',
          loading: true,
          settings: ''
        });
      }
      );
  }

  componentDidUpdate() {
    if (parseInt(this.props.news_widget_id) !== 0) {
      this.initTheme();
    }
  }

  initTheme() {
    if(this.state.settings !== '' && this.state.settings !== 'undefined') {
      let settings = this.state.settings;
      let options;
      let $container = $(`.wplp_widget_${settings.id}`);

      if (settings.theme === 'smooth-effect') {
        let per_page = settings.max_elts;
        let $col;
        if (parseInt(per_page) > 1) {
          $col = parseInt(settings.amount_cols);
          if ($(window).innerWidth() < 400) {
            $col = 1;
          } else if ($(window).innerWidth() < 600) {
            $col = 2;
          }
        } else {
          $col = 1;
        }

        options = {
          itemWidth: 250,
          itemMargin: 10,
          prevText: "",
          nextText: "",
          minItems: $col, // use function to pull in initial value
          maxItems: $col, // use function to pull in initial value
          touch: true
        };

        if (typeof settings.pagination !== 'undefined') {
          switch (settings.pagination) {
            case '0':
              options.controlNav = false;
              options.directionNav = false;
              break;
            case '1':
              options.controlNav = false;
              options.directionNav = true;
              break;
            case '2':
              options.controlNav = true;
              options.directionNav = true;
              break;
            case '3':
              options.controlNav = true;
              options.directionNav = false;
              break;
            }
        }

        if (typeof settings.autoanimation !== 'undefined') {
          switch (settings.autoanimation) {
            case '0':
              options.slideshow = false;
              break;
            case '1':
              options.slideshow = true;
              break;
            }
        }

        // 0 = off
        // 1 = on
        if (typeof settings.autoanimation_trans !== 'undefined') {
          switch (settings.autoanimation_trans) {
            case '0':
              options.animation = "fade";
              break;
            case '1':
              options.animation = "slide";
              break;
            }
        }

        // 0 = true
        // 1 = false
        if (typeof settings.autoanim_loop !== 'undefined') {
          switch (settings.autoanim_loop) {
            case '0':
              options.animationLoop = false;
              break;
            case '1':
              options.animationLoop = true;
              break;
            }
        }

        if (typeof settings.autoanim_pause_hover !== 'undefined') {
          switch (settings.autoanim_pause_hover) {
            case '0':
              options.pauseOnHover = false;
              break;
            case '1':
              options.pauseOnHover = true;
              break;
            }
        }

        if (typeof settings.autoanim_pause_action !== 'undefined') {
          switch (settings.autoanim_pause_action) {
            case '0':
              options.pauseOnAction = false;
              break;
            case '1':
              options.pauseOnAction = true;
              break;
            }
        }

        if (typeof settings.autoanimation_slidedir !== 'undefined') {
          switch (settings.autoanimation_slidedir) {
            case '0':
              options.direction = "horizontal";
              break;
            case '1':
              options.direction = "vertical";
              break;
            }
        }
        if (typeof settings.autoanim_touch_action !== 'undefined') {
          switch (settings.autoanim_touch_action) {
            case '0':
              options.touch = true;
              break;
            case '1':
              options.touch = false;
              break;
            }
        }

        settings.autoanim_slideshowspeed = parseInt(settings.autoanim_slideshowspeed);
        if (typeof settings.autoanim_slideshowspeed !== 'undefined' && !isNaN(settings.autoanim_slideshowspeed)) {
          options.slideshowSpeed = settings.autoanim_slideshowspeed;
        }

        settings.autoanim_slidespeed = parseInt(settings.autoanim_slidespeed);
        if (typeof settings.autoanim_slidespeed !== 'undefined' && !isNaN(settings.autoanim_slidespeed)) {
          options.animationSpeed = settings.autoanim_slidespeed;
        }
      }

      if (settings.theme === 'default') {
        $container.find('.wplp-swiper .swiper-button-next').show();
        $container.find('.wplp-swiper .swiper-button-prev').show();
        $container.find('.wplp-swiper .swiper-pagination'). show();
        $container.find('.wplp-swiper .swiper-button-next, .wplp-swiper .swiper-button-prev').css('color', settings.arrow_color);
        $container.find('.wplp-swiper .swiper-button-next').hover(function() {
          $(this).css('color', settings.arrow_hover_color);
        }, function() {
          $(this).css('color', settings.arrow_color);
        });
        $container.find('.wplp-swiper .swiper-button-prev').hover(function() {
          $(this).css('color', settings.arrow_hover_color);
        }, function() {
          $(this).css('color', settings.arrow_color);
        });
        $container.css('--swiper-pagination-color', settings.arrow_hover_color);

        options = {
          spaceBetween: 15,
          watchOverflow: true,
          // Responsive breakpoints
          breakpoints: {
            // when window width is >= 320px
            320: {
              slidesPerView: 1,
              slidesPerGroup: 1,
              spaceBetweenSlides: parseInt(settings.space_between)
            }
          }
        };

        // 0 = None
        // 1 = Arrows
        // 2 = Arrows with bullets
        // 3 = Bullets
        if (typeof settings.pagination !== 'undefined') {
          switch (settings.pagination) {
            case '0':
              options.navigation = {
                enabled: false
              };
              break;
            case '1':
              options.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              };
              break;
            case '2':
              options.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',

              };
              options.pagination = {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
              };
              break;
            case '3':
              options.navigation = {
                enabled: false
              };
              options.pagination = {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
              };
              break;
            }
        }

        if (typeof settings.space_between !== 'undefined' && !isNaN(settings.space_between)) {
          options.spaceBetween = parseInt(settings.space_between);
        }

        if (typeof settings.layzyload_default !== 'undefined') {
          switch (settings.layzyload_default) {
            case '0':
              options.lazy = false;
              break;
            case '1':
              options.lazy = true;
              options.preloadImages = false;
              break;
            } 
        }

        // animation
        // 0 = fade
        // 1 = slide
        if (typeof settings.autoanimation_trans !== 'undefined') {
          switch (settings.autoanimation_trans) {
            case '0':
              options.effect = 'fade';
              options.fadeEffect = {
                crossFade: true
              };
              break;
            case '1':
              options.effect = "slide";
              break;
            }
        }

        if (typeof settings.autoanim_slidespeed !== 'undefined' && !isNaN(settings.autoanim_slidespeed)) {
          options.speed = parseInt(settings.autoanim_slidespeed);
        }

        if (typeof settings.autoanimation !== 'undefined' && settings.autoanimation === '1'
          && typeof settings.autoanim_slideshowspeed !== 'undefined' && !isNaN(settings.autoanim_slideshowspeed)) {
          options.autoplay = {
            delay: parseInt(settings.autoanim_slideshowspeed)
          };
        }

        if (typeof settings.autoanim_loop !== 'undefined') {
          switch (settings.autoanim_loop) {
            case '0':
              options.loop = false;
              break;
            case '1':
              options.loop = true;
              break;
            }
        }

        if (typeof settings.autoanim_touch_action !== 'undefined') {
          switch (settings.autoanim_touch_action) {
            case '0':
              options.simulateTouch = true;
              break;
            case '1':
              options.simulateTouch = false;
              break;
            }
        }
        
        if (typeof settings.autoanimation_slidedir !== 'undefined') {
          let nbcol = 1;
          let nbrow = 1;
          if (typeof settings.amount_cols !== 'undefined') {
            nbcol = parseInt(settings.amount_cols);
          }
          if (typeof settings.amount_rows !== 'undefined') {
            nbrow = parseInt(settings.amount_rows);
          }

          switch (settings.autoanimation_slidedir) {
          case '0':
            options.direction = 'horizontal';
            if (nbcol > 0 && nbrow > 0) {
              options.slidesPerView = nbcol;
              options.slidesPerGroup = nbcol;

              // fade effect only work well with one slide per view, slidesPerView = 1;
              if (options.slidesPerView > 1 ) {
                options.effect = 'slide';

                if (options.lazy) {
                  options.watchSlidesProgress = true;
                }

                options.breakpoints['768'] = {
                  slidesPerView: 2,
                  slidesPerGroup: 2,
                  spaceBetweenSlides: parseInt(settings.space_between)
                };

                if (options.slidesPerView >= 2) {                                            
                  options.breakpoints['992'] = {
                    slidesPerView: options.slidesPerView,
                    slidesPerGroup: options.slidesPerView,
                    spaceBetweenSlides: parseInt(settings.space_between)
                  };
                }   
              }

              if (nbrow > 1) {
                options.loop = false; // Loop don't work with multiple row
                if (options.effect !== 'fade') {
                  options.grid = {
                    fill: 'row',
                    rows: nbrow
                  }

                  options.breakpoints['320'].grid = {
                    fill: 'row',
                    rows: nbrow
                  };
                  if (typeof options.breakpoints['768'] !== 'undefined') {
                    options.breakpoints['768'].grid = options.breakpoints['320'].grid;
                  }

                  if (typeof options.breakpoints['992'] !== 'undefined') {
                    options.breakpoints['992'].grid = options.breakpoints['320'].grid;
                  }

                } else {
                  options.loop = true;
                }
              }
            }
            break;
          case '1': // vertical: only work with one slide per view
            options.direction = 'vertical';
            break;
          }
        }
      }

      $container.imagesLoaded(function () {
        if (settings.theme === 'default') {
          $(`#wplp_widget_${settings.id}.wplp_widget_default`).wplp_swiper(options, settings);
        }

        if (settings.theme === 'masonry-category') {
          $(`#wplp_widget_${settings.id} .wplp_listposts`).masonry({
            gutter: 10,
            itemSelector: '.masonry-category'
          });
        }

        if (settings.theme === 'masonry') {
          $(`#wplp_widget_${settings.id} .wplp_listposts`).masonry({
            gutter: 10,
            itemSelector: '.masonry'
          });
        }

        if (settings.theme === 'material-vertical') {
          $(`#wplp_widget_${settings.id} .wplp_listposts`).masonry({
            gutter: 20,
            itemSelector: '.material-vertical'
          });
        }

        if (settings.theme === 'portfolio') {
          let $portfolio = $(`#wplp_widget_${settings.id} .wplp_listposts`);
          $portfolio.isotope({
              itemSelector: '.portfolio'
          });

          var delay = 1;
          $portfolio.find('.portfolio').each(function () {
              $(this).find('img').one("load", function () {
                  $(this).parent().delay(delay).queue(function (next) {
                      $(this).addClass('img-loaded', 300);
                      next();
                  });
                  delay += 200;

              }).each(function () {
                  if (this.complete) {
                      $(this).load();
                  }
              });
          });
        }

        if (settings.theme === 'smooth-effect') {
          if ($().flexslider) {
            $(`#wplp_widget_${settings.id}.wplp_widget_smooth-effect`).flexslider(options);
          }
        }
      });
    }
    return;
  }

  render() {
    const loadingIcon = (
        <svg className={'wpfd-loading'} width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <g transform="translate(25 50)">
                <circle cx="0" cy="0" r="10" fill="#cfcfcf" transform="scale(0.590851 0.590851)">
                    <animateTransform attributeName="transform" type="scale" begin="-0.8666666666666667s" calcMode="spline"
                                      keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0.5;1;0.5" keyTimes="0;0.5;1" dur="2.6s"
                                      repeatCount="indefinite"/>
                </circle>
            </g>
            <g transform="translate(50 50)">
                <circle cx="0" cy="0" r="10" fill="#cfcfcf" transform="scale(0.145187 0.145187)">
                    <animateTransform attributeName="transform" type="scale" begin="-0.43333333333333335s" calcMode="spline"
                                      keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0.5;1;0.5" keyTimes="0;0.5;1" dur="2.6s"
                                      repeatCount="indefinite"/>
                </circle>
            </g>
            <g transform="translate(75 50)">
                <circle cx="0" cy="0" r="10" fill="#cfcfcf" transform="scale(0.0339143 0.0339143)">
                    <animateTransform attributeName="transform" type="scale" begin="0s" calcMode="spline"
                                      keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0.5;1;0.5" keyTimes="0;0.5;1" dur="2.6s"
                                      repeatCount="indefinite"/>
                </circle>
            </g>
        </svg>
    );

    if (parseInt(this.props.news_widget_id) === 0) {
      return (
        <div className="wplp-divi-container wplp-divi-wrap" ref={this.wrap}>
            <div id="wplp-divi-placeholder" className="wplp-divi-placeholder"></div>
            <div>
              <span className="wplp-divi-message">
                {'Please select a WP Latest Posts new block to activate the preview'}
              </span>
            </div>
        </div>
      );
    } 

    if (this.state.loading) {
        return (
            <div className="wplp-divi-container wplp-divi-wrap" ref={this.wrap}>
                <div className={'wplp-loading-wrapper'}>
                    <i className={'wplp-loading'}>{loadingIcon}</i>
                </div>
            </div>
        );
    }

    if (!this.state.loading) {
        return (
            <div className="wp-latest-posts-block-preview" ref={this.wrap}
                 dangerouslySetInnerHTML={{__html: this.state.html}}/>
        );
    }
  }
}

export default WplpDivi;