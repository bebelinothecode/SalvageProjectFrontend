import React,{useState, useEffect} from 'react';

const Slideshow = ({images}) => {
    const [currentIndex, setCurrentIndex] = useState(0);

    useEffect(() => {
        const interval = setInterval(() => {
          setCurrentIndex((prevIndex) =>
            prevIndex === images.length - 1 ? 0 : prevIndex + 1
          );
        }, 3000); // Change image every 3 seconds
        return () => clearInterval(interval);
      }, [images.length]);
    
      return (
        <div className="relative min-w-[240px] min-h-[440px] lg:overflow-visible rounded border bg-white p-1 dark:border-neutral-700 dark:bg-neutral-800">
          {images.map((image, index) => (
            <img
              key={index}
              src={image}
              alt={`Slide ${index}`}
              className={`absolute inset-0 w-full object-contain h-full transition-opacity duration-1000 ${
                index === currentIndex ? 'opacity-100' : 'opacity-0'
              }`}
            />
          ))}
          <div className="absolute inset-0 flex justify-between items-center px-4">
            <button
              className="bg-gray-800 text-white p-2 rounded-full"
              onClick={() =>
                setCurrentIndex((prevIndex) =>
                  prevIndex === 0 ? images.length - 1 : prevIndex - 1
                )
              }
            >
              &#10094;
            </button>
            <button
              className="bg-gray-800 text-white p-2 rounded-full"
              onClick={() =>
                setCurrentIndex((prevIndex) =>
                  prevIndex === images.length - 1 ? 0 : prevIndex + 1
                )
              }
            >
              &#10095;
            </button>
          </div>
        </div>
      );
    };
    
export default Slideshow;
