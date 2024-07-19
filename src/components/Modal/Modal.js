import React, {useState} from 'react';
import tesla1 from '../../Images/tesla1.jpeg';
import tesla2 from '../../Images/tesla2.jpeg';
import tesla3 from '../../Images/tesla3.jpeg';
import Snackbar from '@mui/material/Snackbar';
import Slideshow from '../ImageSlideShow/imageslide';
import BasicTable from '../Table/Table';
import Divider from '@mui/material/Divider';
import ModalClose from '@mui/joy/ModalClose';



const  PopModal = ({onClose}) => {

    const [currentDate, setCurrentDate] = useState(new Date());
    const [open, setOpen] = useState(false);
    const [isModalOpen, setIsModalOpen] = useState(false);


    const images = [
        tesla1,
        tesla2,
        tesla3,
    ];

    
    const handleClick = () => {
        setOpen(true);
    };

    function getRandomInt(min, max) {
        const minCeiled = Math.ceil(min);
        const maxFloored = Math.floor(max);
        return Math.floor(Math.random() * (maxFloored - minCeiled) + minCeiled); // The maximum is exclusive and the minimum is inclusive
    }

    const handleClose = (event: React.SyntheticEvent | Event, reason?: string) => {
        if (reason === 'clickaway') {
          return;
        }
    
        setOpen(false);
      };


  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"> 
            <div className="relative w-auto my-6 mx-auto max-w-3xl">
              <div className="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                <div className="flex items-center p-5 border-b border-solid border-blueGray-200 rounded-t">
                  <h3 className="text-3xl font-semibold">
                    Salvage Car Details
                  </h3>
                </div>
                <div class="flex justify-between">
                <div className="container mx-auto p-4">
                   <h1 className="text-2xl font-bold mb-4">Images of Salvage</h1>
                   <Slideshow images={images} />
                </div>
                    <Divider className='m-3' orientation="vertical" flexItem />
                    <div>
                    <h5 className="flex items-center text-2xl font-semibold px-10 py-4">
                        Salvage Information
                    </h5>
                    <Divider className='' flexItem/>
                    <div className='m-3 my-6'>
                        <BasicTable/>
                    </div>
                        <Divider/>
                        <p className='text-wrap font-bold font-mono m-1 p-1 font-2xl'>Bidding Information</p>
                        <div className="md:container md:mx-auto bg-slate-300 m-1 p-2">
                            <div className='flex justify-between'>
                                <div>
                                   <p className='text-wrap font-bold font-mono m-1 p-1 font-xl'>Number of Bidders</p>
                                </div>
                                <div>
                                    <p className='text-wrap font-bold font-mono m-1 p-1 font-xl'>100</p>
                                </div>
                            </div>
                            <Divider />
                            <div className='flex justify-between'>
                                <div>
                                   <p className='text-wrap font-bold font-mono m-1 p-1 font-xl'>Bid Closing Date</p>
                                </div>
                                <div>
                                  <p className='text-red-500 text-lg animate-pulse'>{currentDate.toDateString()}</p>
                                </div>
                            </div>
                            <Divider />
                            <div className='flex justify-between'>
                                <div>
                                   <p className='text-wrap font-bold font-mono m-1 p-1 font-xl'>Highest Bid Placed</p>
                                </div>
                                <div>
                                  <p className='text-black text-lg'>GHS{getRandomInt(1000, 60000)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
                  <ModalClose onClick={onClose}/>

                  <button
                    className="bg-blue-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button"
                    onClick={handleClick}
                  >
                    Place Bid
                  </button>
                  <Snackbar
                    open={open}
                    autoHideDuration={5000}
                    onClose={handleClose}
                    message="Your bid has been placed successfully"
                />
                </div>
              </div>
            </div>
        </div>
    )
}

export default PopModal