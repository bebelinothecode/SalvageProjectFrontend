import React from 'react'

const Disclaimer = ({style, buttonStyle, buttonText}) => {
  return (
     //   <div class="bg-white  m flex justify-end">
     //        <div>
     //           <button className="bg-blue-400 text-white px-4 py-2 rounded m-2">Sales Disclaimer</button>
     //        </div>
     //   </div>
     <div className={`${style}`}>
          <div>
               <button className={`${buttonStyle}`}>
                    {buttonText}
               </button>
          </div>
     </div>
  )
}

export default Disclaimer
