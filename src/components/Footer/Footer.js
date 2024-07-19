import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-gray-800 p-4 sticky">
      <div className="container mx-auto flex flex-col md:flex-row justify-between items-center">
        <div className="text-white text-center md:text-left">
          &copy; {new Date().getFullYear()} Phrontlyne Technologies. All rights reserved.
        </div>
        <nav className="flex space-x-4 mt-2 md:mt-0">
          {/* <a href="#" className="text-gray-400 hover:text-white">Privacy Policy</a> */}
          {/* <a href="#" className="text-gray-400 hover:text-white">Terms of Service</a> */}
          <a href="#" className="text-gray-400 hover:text-white">Contact Us</a>
        </nav>
        <div className="text-white mt-2 md:mt-0">
          Follow us:
          <a href="#" className="ml-2 text-gray-400 hover:text-white">Facebook</a>
          <a href="#" className="ml-2 text-gray-400 hover:text-white">Twitter</a>
          <a href="#" className="ml-2 text-gray-400 hover:text-white">Instagram</a>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
