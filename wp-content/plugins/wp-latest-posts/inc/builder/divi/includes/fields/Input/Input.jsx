// External Dependencies
import React, { Component, Fragment } from 'react';

// Internal Dependencies
import './style.css';

class Input extends Component {

  static slug = 'wplp_input';

  constructor(props) {
    super(props);
    this.state = {
      selectValue: '0',
      blockList: '',
      error: false
    };
    
    this.handleChange = this.handleChange.bind(this);
    this.fetchBlocks = this.fetchBlocks.bind(this);
    this.openSetting = this.openSetting.bind(this);
  }

  componentDidMount() {
    if (this.props.name === 'news_widget_id') {
      this.fetchBlocks();
      this.openSetting();
    }
  }

  openSetting() {
    let selectValue = 0;
    if (this.props.name === 'news_widget_id') {
      selectValue = this.props.value !== '0'
          ? this.props.value : 0;
      this.setState({selectValue: selectValue});
    }
  }

  /**
   * Handle input value change.
   *
   * @param {object} event
   */
  _onChange = (value) => {
    this.props._onChange(this.props.name, value);
  }

  handleChange(e) {
    this.setState({selectValue:e.target.value});
    this.setSelectedBlock(e.target.value);
  }

  fetchBlocks() {
    const self = this;
    const url = window.et_fb_options.ajaxurl + `?action=wplp_get_list_block&wplp_get_list_block=true`;

    if (this.state.error) {
      this.setState({error: false})
    }

    fetch(url).then(function (response) {
      return response.json()
    }).then(function (response) {
      if (false === response.success) {
        self.setState({
          error: true,
        })
      } else {
        self.setState({
          blockList: response.list,
          adminUrl: typeof response.adminUrl !== 'undefined' ? response.adminUrl : ''
        });
      }
    }).catch(function (error) {
      self.setState({
        error: true,
      })
    })
  }

  setSelectedBlock(value) {
    if (value !== '') {
      this.setState({selectValue: value});
      this._onChange(value);
    }
  }

  render() {
    const {blockList, adminUrl, selectValue} = this.state;
    return(
      <Fragment>
        <select
          className="wplp-divi-select"
          value={selectValue} 
          onChange={this.handleChange}
          dangerouslySetInnerHTML={{__html: blockList}}
        />
        {
          parseInt(selectValue) > 0
          ? <div className="wplp-divi-link-edit">{`Open and edit the news block settings from the plugin `}<a href={adminUrl + `&view=block&id=${selectValue}`} target="_blank">{`here`}</a></div>
          : ''
        }
      </Fragment>
    );
  }
}

export default Input;
