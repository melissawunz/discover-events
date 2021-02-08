import React from 'react';
import Select from 'react-select';

class GeneralFilter extends React.Component{
    render() {
        return (
            <div className="general-select">
                <Select
                    placeholder={this.props.placeHolder}
                    defaultValue={this.props.options[0]}
                    name="general"
                    options={this.props.options}
                    onChange={(item) => this.props.handleClick(item, this.props.label)}
                    isClearable
                    value={this.props.selectedValue}
                />
            </div>
        );
    }
}

export default GeneralFilter;