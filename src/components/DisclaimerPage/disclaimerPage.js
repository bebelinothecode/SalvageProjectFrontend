import React from 'react'
import SibaHeader from '../header/Header'
import Footer from '../Footer/Footer'

function disclaimerPage({styles, paragraphStyle, $paragraphText}) {
  return (
    <div className={`${styles}`}>
      <SibaHeader/>
      <main>
        <disclaimer/>
      </main>
      <Footer/>
    </div>
  )
}

export default disclaimerPage