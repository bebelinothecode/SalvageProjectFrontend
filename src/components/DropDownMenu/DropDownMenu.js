import React, {useState} from 'react';
import  Select  from "flowbite-react";

const DropDownMenu = () => {
    const [selectedOption, setSelectedOption] = useState('');

    const handleChange = (event) => {
      setSelectedOption(event.target.value);
    };

    return (
      <Select value={selectedOption} onChange={handleChange}>
          <option  >--Select option--</option>
          <option  value="Male">Male</option>
          <option  value="Female">Female</option>
          <option  value="Other">Other</option>
      </Select>
    );
  }

  export default DropDownMenu