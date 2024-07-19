// import React, {useState} from 'react'

// function Exercise() {
//     const [option, setOption] =useState('');

//     const handleChange = (event) => {
//         setOption(event.target.value);
//     }
//     return  (
//         <div>
//             <label>
//                 Select an option:
//                     <select  value={option} onChange={handleChange}>
//                     <option  value="option1">Option 1</option>
//                     <option  value="option2">Option 2</option>
//                     <option  value="option3">Option 3</option>
//                 </select>
//             </label>
//             <p>Selected option: {option}</p>
//         </div>
//     );
// }

// export default Exercise

import React, {useState} from "react";


function Exercise() {
    const [inputValue, setInputValue] = useState('');
    
    const handleChange = (event) => {
        setInputValue(event.target.value)
    }

    return (
        <form>
            <label htmlFor="">InputValue:
                <input class="outline" type="text" value={inputValue} onChange={handleChange} />
            </label>
            <label htmlFor="">Input Value:{inputValue}</label>
        </form>
    );
}

export default Exercise;