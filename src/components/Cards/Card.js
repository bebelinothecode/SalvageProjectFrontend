import React, {useState, useEffect} from 'react';
import carlogo from '../../Images/CardImage/damagedTesla.jpeg';
import Divider from '@mui/material/Divider';
import Chip from '@mui/material/Chip';
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth'; 
import PopModal from "../Modal/Modal";



const Card = ({ title, description, buttonText, LearnMore }) => {
    const [currentDate, setCurrentDate] = useState(new Date());
    const [showModal, setShowModal] = useState(false);
    const [data, setData] = useState([]);
    const [error, setError] = useState(null);

    const fetchData = async() => {
      try {
        const response = await fetch("http://127.0.0.1:8000/api/bebelino");

        if(!response.ok) {
          throw new Error("Network error");
        }

        const data = await response.json();

        setData(data);
        console.log(data)
        console.log(Array.isArray(data));
      } catch (error) {
        console.error("Problem getting data:",error);
      }
    }

    useEffect(() => {
      fetchData();
    }, []); 
      
    return (
    <div class="mx-auto border-solid border-2 border-sky-500 max-w-sm  bg-white rounded-lg shadow-md overflow-hidden">
      <img className="rounded-sm px-3 py-3" src={carlogo} alt="Card image cap" />
      <div className="px-4 py-4">
        <div className="font-bold text-xl mb-1">{title}</div>
        <p>
          {data.map((item) => (
            <div key={item.id}>
              <p>{item.status}</p>
              {
                data.map(dt => {
                  <div key={dt.id}>
                    <h6>Policy Number:{dt.policy_number}</h6>
                  </div>
                })
              }
            </div>
          ))}
        </p>
        <p className="text-gray-700 text-base text-wrap text-ellipsis overflow-hidden">
          {description}
        </p>
      </div>
      <div className="p-4">
         <Divider className='mx-5 my-5'/>
      <div>
         <Chip className='mt-3' label="Starting Bid Price" variant="filled" />
         <p className='font-mono m-2'>GHS 90,000.00</p>
      </div>
        <Divider  />
      <div className="flex justify-around">
        <div>
           <p className='font-mono m-2 text-sm'>Status</p>
           <Chip label="Available" color="success" size="small"/>
        </div>
        <div>
          <p className='font-mono m-2'>Manu. Year</p>
          <p className='font-mono m-2 text-sm font-bold'>2012</p>
        </div>
        <div>
            <p className='font-mono m-2'>Stock Num.</p>
            <p className='font-mono m-2 text-sm font-bold'>368929</p>
        </div>
      </div>
      <Divider />
         <p className='font-mono m-2 font-bold'>Bid Closing Date</p>
         <div class="flex justify-between">
            <p class="text-red-500 text-lg animate-pulse">{currentDate.toDateString()}</p>
            <CalendarMonthIcon/>
         </div>
      </div>
      <div class="flex justify-center">
        <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 m-3 rounded">
          {buttonText}
        </button>
        <button onClick={() => setShowModal(true)}  className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 m-3 rounded">
          {LearnMore}
        </button>
        {showModal ? (
        <>
        <PopModal
          onClose={()=> setShowModal(false)}
          />
        </>
      ) : null}
      </div>   
    </div>
  );
}

export default Card;


