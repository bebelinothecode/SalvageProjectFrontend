import React from 'react';

function SearchBar() {
  return (
<form >
    <input name="query" className='outine  bg-white border border-black text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:Search salvages here' />
    <button type="submit" className='bg-blue-500 text-white font-bold py-2 px-4 m-2 rounded-full'>Search</button>
</form> 
  );
}

export default SearchBar


{/* <form action={search}> */}




// return (
//     <div className='flex justify-around'>
//         <div className='flex-col mx-3 md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full '>
//             <TextInput
//                 id='search'
//                 type='text'
//                 placeholder='Search for salvages...'
//                 // value={}
//                 // onChange={}
//                 className='w-full md:w-2/3 px-2 py-2 border rounded-md'
//             />
//         </div>
//         <div>
//             <button
//                 type='submit'
//                 className="px-2 my-2 text-white bg-blue-500 rounded-md hover:bg-blue-700 sm:px-4 sm:py-2 md:px-6 md:py-3 lg:px-4 lg:py-3">
//                 Search
//             </button>
//         </div>
//     </div>
        // <TextInput
        //     id='search'
        //     type='text'
        //     placeholder='Search for salvages...'
        //     // value={}
        //     // onChange={}
        //     className='w-full px-4 py-2 border rounded-md'
        // />
        // <button
        //     type='submit'
        //     className="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-700">
        //     Search
        // </button>