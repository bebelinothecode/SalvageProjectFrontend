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


