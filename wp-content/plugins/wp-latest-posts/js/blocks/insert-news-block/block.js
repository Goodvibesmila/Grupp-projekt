'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

(function (wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
    var __ = wp.i18n.__;
    var Component = wpElement.Component,
        Fragment = wpElement.Fragment;
    var registerBlockType = wpBlocks.registerBlockType;
    var BlockControls = wpEditor.BlockControls;
    var TextControl = wpComponents.TextControl,
        Toolbar = wpComponents.Toolbar,
        IconButton = wpComponents.IconButton;

    var $ = jQuery;
    var el = wpElement.createElement;
    var iconblock = el('svg', { width: 24, height: 24, className: 'dashicon' }, el('path', { d: "M22 13h-8v-2h8v2zm0-6h-8v2h8V7zm-8 10h8v-2h-8v2zm-2-8v6c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2zm-1.5 6l-2.25-3-1.75 2.26-1.25-1.51L3.5 15h7z" }));

    var WplpNews = function (_Component) {
        _inherits(WplpNews, _Component);

        /**
         * Constructor
         */
        function WplpNews() {
            _classCallCheck(this, WplpNews);

            var _this = _possibleConstructorReturn(this, (WplpNews.__proto__ || Object.getPrototypeOf(WplpNews)).apply(this, arguments));

            _this.state = {
                isOpenList: false,
                searchValue: '',
                preview: false
            };

            _this.setWrapperRef = _this.setWrapperRef.bind(_this);
            _this.handleClickOutside = _this.handleClickOutside.bind(_this);
            return _this;
        }

        _createClass(WplpNews, [{
            key: 'componentWillMount',
            value: function componentWillMount() {
                var attributes = this.props.attributes;
                var newsID = attributes.newsID;

                this.initLoadTheme(newsID);
            }
        }, {
            key: 'componentDidMount',
            value: function componentDidMount() {
                var attributes = this.props.attributes;
                var shortcode = attributes.shortcode;


                this.setState({
                    searchValue: shortcode
                });

                document.addEventListener('mousedown', this.handleClickOutside);
            }
        }, {
            key: 'componentDidUpdate',
            value: function componentDidUpdate() {
                this.initTheme();
            }
        }, {
            key: 'initLoadTheme',
            value: function initLoadTheme(newsID) {
                var _this2 = this;

                if (parseInt(newsID) !== 0) {
                    var setAttributes = this.props.setAttributes;

                    fetch(wplp_blocks.vars.ajaxurl + ('?action=wplp_load_html&id=' + newsID)).then(function (res) {
                        return res.json();
                    }).then(function (result) {
                        if (result.status) {
                            setAttributes({
                                html: result.html,
                                settings: result.settings
                            });

                            _this2.setState({
                                preview: true
                            });
                        }
                    },
                    // errors
                    function (error) {});
                }
            }
        }, {
            key: 'initTheme',
            value: function initTheme() {
                var _props = this.props,
                    attributes = _props.attributes,
                    clientId = _props.clientId;
                var settings = attributes.settings;
                var $container = $('#block-' + clientId + ' .wp-latest-posts-block-preview');
                var options = void 0;

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
                    if (typeof settings.pagination != 'undefined') {
                        switch (parseInt(settings.pagination)) {
                            case 0:
                                options.navigation = {
                                    enabled: false
                                };
                                break;
                            case 1:
                                options.navigation = {
                                    nextEl: '.swiper-button-next',
                                    prevEl: '.swiper-button-prev',
                                };
                                break;
                            case 2:
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
                            case 3:
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

                    if (typeof settings.space_between != 'undefined' && !isNaN(settings.space_between)) {
                        options.spaceBetween = parseInt(settings.space_between);
                    }

                    if (typeof settings.layzyload_default != 'undefined') {
                        switch (parseInt(settings.layzyload_default)) {
                            case 0:
                                options.lazy = false;
                                break;
                            case 1:
                                options.lazy = true;
                                options.preloadImages = false;
                                break;
                        } 
                    }

                    // animation
                    // 0 = fade
                    // 1 = slide
                    if (typeof settings.autoanimation_trans != 'undefined') {
                        switch (parseInt(settings.autoanimation_trans)) {
                            case 0:
                                options.effect = 'fade';
                                options.fadeEffect = {
                                    crossFade: true
                                };
                                break;
                            case 1:
                                options.effect = "slide";
                                break;
                        }
                    }

                    if (typeof settings.autoanim_slidespeed != 'undefined' && !isNaN(settings.autoanim_slidespeed)) {
                        options.speed = parseInt(settings.autoanim_slidespeed);
                    }

                    if (typeof settings.autoanimation != 'undefined' && settings.autoanimation == '1'
                        && typeof settings.autoanim_slideshowspeed != 'undefined' && !isNaN(settings.autoanim_slideshowspeed)) {
                        options.autoplay = {
                            delay: parseInt(settings.autoanim_slideshowspeed)
                        };
                    }

                    if (typeof settings.autoanim_loop != 'undefined') {
                        switch (parseInt(settings.autoanim_loop)) {
                            case 0:
                                options.loop = false;
                                break;
                            case 1:
                                options.loop = true;
                                break;
                        }
                    }

                    if (typeof settings.autoanim_touch_action != 'undefined') {
                        switch (parseInt(settings.autoanim_touch_action)) {
                            case 0:
                                options.simulateTouch = true;
                                break;
                            case 1:
                                options.simulateTouch = false;
                                break;
                        }
                    }
                    
                    if (typeof settings.autoanimation_slidedir != 'undefined') {
                        let nbcol = 1;
                        let nbrow = 1;
                        if (typeof settings.amount_cols != 'undefined') {
                            nbcol = parseInt(settings.amount_cols);
                        }
                        if (typeof settings.amount_rows != 'undefined') {
                            nbrow = parseInt(settings.amount_rows);
                        }

                        switch (parseInt(settings.autoanimation_slidedir)) {
                            case 0:
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
                                            if (typeof options.breakpoints['768'] != 'undefined') {
                                                options.breakpoints['768'].grid = options.breakpoints['320'].grid;
                                            }

                                            if (typeof options.breakpoints['992'] != 'undefined') {
                                                options.breakpoints['992'].grid = options.breakpoints['320'].grid;
                                            }

                                        } else {
                                            options.loop = true;
                                        }
                                    }
                                }
                                break;
                            case 1: // vertical: only work with one slide per view
                                options.direction = 'vertical';
                                break;
                        }
                    }
                }

                if (settings.theme === 'smooth-effect') {
                    var $col = parseInt(settings.amount_cols);
                    if (typeof $col === "undefined") {
                        $col = 4;
                    }

                    if ($col > 3) {
                        if ($(".wplp_outside.wplp_widget_" + settings.id).innerWidth() < 600) {
                            $col = 2;
                        } else if ($(".wplp_outside.wplp_widget_" + settings.id).innerWidth() < 900) {
                            $col = 3;
                        } else {
                            $col = 4;
                        }
                    }

                    if ($(".wplp_outside.wplp_widget_" + settings.id).innerWidth() < 400) {
                        $col = 1;
                    }

                    var itemWidth = 250, itemMargin = 10;
                    
                    if ($col === 1) {
                        itemWidth = $(".wplp_outside.wplp_widget_" + settings.id).innerWidth();
                        itemMargin = 0;
                    } else {
                        var allGutters = itemMargin * ($col - 1);
                        var contentWidth = $(".wplp_outside.wplp_widget_" + settings.id).innerWidth() - allGutters;
                        itemWidth = Math.floor(contentWidth / $col);
                    }
                    
                    options = {
                        itemWidth: itemWidth,
                        itemMargin: itemMargin,
                        prevText: "",
                        nextText: "",
                        minItems: $col, // use function to pull in initial value
                        maxItems: $col, // use function to pull in initial value
                        touch: true
                    };

                    if (typeof settings.pagination != 'undefined') {
                        switch (parseInt(settings.pagination)) {
                            case 0:
                                options.controlNav = false;
                                options.directionNav = false;
                                break;
                            case 1:
                                options.controlNav = false;
                                options.directionNav = true;
                                break;
                            case 2:
                                options.controlNav = true;
                                options.directionNav = true;
                                break;
                            case 3:
                                options.controlNav = true;
                                options.directionNav = false;
                                break;
                        }
                    }

                    if (typeof settings.autoanimation != 'undefined') {
                        switch (parseInt(settings.autoanimation)) {
                            case 0:
                                options.slideshow = false;
                                break;
                            case 1:
                                options.slideshow = true;
                                break;
                        }
                    }

                    // 0 = off
                    // 1 = on
                    options.animation = "slide";

                    // 0 = true
                    // 1 = false
                    if (typeof settings.autoanim_loop != 'undefined') {
                        switch (parseInt(settings.autoanim_loop)) {
                            case 0:
                                options.animationLoop = false;
                                break;
                            case 1:
                                options.animationLoop = true;
                                break;
                        }
                    }

                    if (typeof settings.autoanim_pause_hover != 'undefined') {
                        switch ((settings.autoanim_pause_hover)) {
                            case 0:
                                options.pauseOnHover = false;
                                break;
                            case 1:
                                options.pauseOnHover = true;
                                break;
                        }
                    }

                    if (typeof settings.autoanim_pause_action != 'undefined') {
                        switch ((settings.autoanim_pause_action)) {
                            case 0:
                                options.pauseOnAction = false;
                                break;
                            case 1:
                                options.pauseOnAction = true;
                                break;
                        }
                    }

                    options.direction = "horizontal";
                
                    if (typeof settings.autoanim_touch_action != 'undefined') {
                        switch ((settings.autoanim_touch_action)) {
                            case 0:
                                options.touch = true;
                                break;
                            case 1:
                                options.touch = false;
                                break;
                        }
                    }

                    settings.autoanim_slideshowspeed = parseInt(settings.autoanim_slideshowspeed);
                    if (typeof settings.autoanim_slideshowspeed != 'undefined' && !isNaN(settings.autoanim_slideshowspeed)) {
                        options.slideshowSpeed = settings.autoanim_slideshowspeed;
                    }

                    settings.autoanim_slidespeed = parseInt(settings.autoanim_slidespeed);
                    if (typeof settings.autoanim_slidespeed != 'undefined' && !isNaN(settings.autoanim_slidespeed)) {
                        options.animationSpeed = settings.autoanim_slidespeed;
                    }

                
                    if (jQuery().flexslider) {
                        $('#block-' + clientId + ' .wp-latest-posts-block-preview .wplp_widget_smooth-effect').flexslider(options);
                        $('.wplp_widget_smooth-effect ul.wplp_listposts li').css('margin-right', '10px');
                    }
                }

                imagesLoaded($container, function () {
                    if (settings.theme === 'default') {
                        var mySwiper = new Swiper('#block-' + clientId + ' .wp-latest-posts-block-preview .wplp_widget_default', options);
                    }

                    if (settings.theme === 'masonry-category') {
                        $('#block-' + clientId + ' .wplp_listposts').masonry({
                            gutter: 10,
                            itemSelector: '.masonry-category'
                        });
                    }

                    if (settings.theme === 'masonry') {
                        $('#block-' + clientId + ' .wplp_listposts').masonry({
                            gutter: 10,
                            itemSelector: '.masonry'
                        });
                    }

                    if (settings.theme === 'material-vertical') {
                        $('#block-' + clientId + ' .wplp_listposts').masonry({
                            gutter: 20,
                            itemSelector: '.material-vertical'
                        });
                    }

                    if (settings.theme === 'portfolio') {
                        var $portfolio = $('#block-' + clientId + ' .wplp_listposts');
                        $portfolio.isotope({
                            itemSelector: '.portfolio'
                        });

                        var delay = 1;
                        $portfolio.find('.portfolio').each(function () {
                            jQuery(this).find('img').one("load", function () {
                                jQuery(this).parent().delay(delay).queue(function (next) {
                                    jQuery(this).addClass('img-loaded', 300);
                                    next();
                                });
                                delay += 200;
                            }).each(function () {
                                if (this.complete) {
                                    jQuery(this).load();
                                }
                            });
                        });
                    }

                    
                });
            }

            /**
             * Set the wrapper ref
             */

        }, {
            key: 'setWrapperRef',
            value: function setWrapperRef(node) {
                this.wrapperRef = node;
            }

            /**
             * Alert if clicked on outside of element
             */

        }, {
            key: 'handleClickOutside',
            value: function handleClickOutside(event) {
                if (this.wrapperRef && !this.wrapperRef.contains(event.target)) {
                    var _props2 = this.props,
                        attributes = _props2.attributes,
                        setAttributes = _props2.setAttributes;
                    var shortcode = attributes.shortcode;


                    this.setState({
                        isOpenList: false,
                        searchValue: shortcode
                    });
                    setAttributes({
                        shortcode: shortcode
                    });
                }
            }

            /**
             * Select news post
             */

        }, {
            key: 'selectPost',
            value: function selectPost(value) {
                var setAttributes = this.props.setAttributes;

                this.setState({
                    isOpenList: false,
                    searchValue: '[frontpage_news widget="' + value + '"]',
                    preview: false
                });

                setAttributes({
                    newsID: value.toString(),
                    shortcode: '[frontpage_news widget="' + value + '"]'
                });

                this.initLoadTheme(value.toString());
            }

            /**
             * DO search news post
             */

        }, {
            key: 'search',
            value: function search(value) {
                var setAttributes = this.props.setAttributes;

                var newsSearchList = wplp_blocks.vars.posts_select.filter(function (event) {
                    return event.label.toLowerCase().indexOf(value.toLowerCase()) > -1;
                });

                this.setState({ searchValue: value });

                setAttributes({
                    newsList: newsSearchList
                });
            }

            /**
             * Click to search input
             */

        }, {
            key: 'handleClick',
            value: function handleClick() {
                var setAttributes = this.props.setAttributes;

                setAttributes({
                    newsList: wplp_blocks.vars.posts_select
                });

                this.setState({
                    isOpenList: true,
                    searchValue: ''
                });
            }

            /**
             * Render block
             */

        }, {
            key: 'render',
            value: function render() {
                var _this3 = this;

                var _props3 = this.props,
                    attributes = _props3.attributes,
                    clientId = _props3.clientId;
                var newsList = attributes.newsList,
                    newsID = attributes.newsID,
                    html = attributes.html,
                    cover = attributes.cover;


                var controls = React.createElement(
                    BlockControls,
                    null,
                    newsID !== '0' && React.createElement(
                        Toolbar,
                        null,
                        React.createElement(IconButton, {
                            className: 'components-toolbar__control',
                            label: __('Edit', 'wp-latest-posts'),
                            icon: 'edit',
                            onClick: function onClick() {
                                return window.open(wplp_blocks.vars.edit_url + newsID, '_blank');
                            }
                        }),
                        React.createElement(IconButton, {
                            className: 'components-toolbar__control',
                            label: __('Refresh', 'wp-latest-posts'),
                            icon: 'update',
                            onClick: function onClick() {
                                return _this3.initLoadTheme(newsID);
                            }
                        })
                    )
                );

                return React.createElement(
                    Fragment,
                    null,
                    controls,
                    typeof cover !== "undefined" && React.createElement(
                        'div',
                        { className: 'wplp-cover' },
                        React.createElement('img', { src: cover })
                    ),
                    typeof cover === "undefined" && React.createElement(
                        'div',
                        { className: 'wp-block-shortcode', ref: this.setWrapperRef },
                        React.createElement(
                            'label',
                            null,
                            iconblock,
                            ' ',
                            wplp_blocks.l18n.block_title
                        ),
                        React.createElement(
                            'div',
                            { className: 'wp-latest-posts-block' },
                            React.createElement(TextControl, {
                                value: this.state.searchValue,
                                className: 'wplp_search_news',
                                placeholder: wplp_blocks.l18n.select_label,
                                autoComplete: 'off',
                                onChange: this.search.bind(this),
                                onClick: this.handleClick.bind(this)
                            }),
                            this.state.isOpenList && newsList.length && React.createElement(
                                'ul',
                                { className: 'wp-latest-posts-list' },
                                newsList.map(function (post) {
                                    return React.createElement(
                                        'li',
                                        { className: newsID.toString() === post.value.toString() ? 'news_post_item news_post_item_active' : 'news_post_item',
                                            'data-id': post.value,
                                            key: post.value,
                                            onClick: _this3.selectPost.bind(_this3, post.value) },
                                        post.label
                                    );
                                })
                            ),
                            this.state.isOpenList && !newsList.length && React.createElement(
                                'ul',
                                { className: 'wp-latest-posts-list' },
                                React.createElement(
                                    'li',
                                    { key: '0' },
                                    wplp_blocks.l18n.no_post_found
                                )
                            )
                        )
                    ),
                    this.state.preview && React.createElement('div', { className: 'wp-latest-posts-block-preview', dangerouslySetInnerHTML: { __html: html } }),
                    !this.state.preview && newsID !== '0' && React.createElement('div', { className: 'wp-latest-posts-block-preview', dangerouslySetInnerHTML: { __html: '<p class="wplp_block_loading">' + __('Loading...', 'wp-latest-posts') + '</p>' } })
                );
            }
        }]);

        return WplpNews;
    }(Component);

    // register block


    registerBlockType('wplp/block-news', {
        title: wplp_blocks.l18n.block_title,
        description: __('Load your content from posts, page, tags or custom post type and display them as a slider', 'wp-latest-posts'),
        icon: iconblock,
        category: 'common',
        keywords: [__('post', 'wp-latest-posts'), __('wplp', 'wp-latest-posts')],
        example: {
            attributes: {
                cover: wplp_blocks.vars.block_cover
            }
        },
        attributes: {
            newsList: {
                type: 'array',
                default: wplp_blocks.vars.posts_select
            },
            newsID: {
                type: 'string',
                default: '0'
            },
            shortcode: {
                type: 'string',
                default: ''
            },
            html: {
                type: 'string',
                default: ''
            },
            settings: {
                type: 'object',
                default: {}
            },
            cover: {
                type: 'string',
                source: 'attribute',
                selector: 'img',
                attribute: 'src'
            }
        },
        edit: WplpNews,
        save: function save(_ref) {
            var attributes = _ref.attributes;
            var shortcode = attributes.shortcode;

            return shortcode;
        }
    });
})(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components);
