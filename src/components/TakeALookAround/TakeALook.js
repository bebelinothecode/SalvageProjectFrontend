import React from 'react'
import SearchBar from '../SearchBar/SearchBar';

function TakeALook() {
  return (
<div class=" flex justify-between p-5">
    <div className='mx-6 md:mx-2 lg:mx-8'>
         <p class='text-wrap font-mono m-2 md:m-4 md:text-2xl lg:text-2xl font-bold text-gray-700 text-ellipsis overflow-hidden'>Take a look around</p>
    </div>
    <div>
      <SearchBar/>
    </div>
</div>
  );
}

export default TakeALook