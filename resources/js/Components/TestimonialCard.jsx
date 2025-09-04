export default function TestimonialCard({ testimoni }) {
    return (
        <div className="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg text-center h-full flex flex-col transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div className="flex justify-center -mt-16 mb-4">
                {/* Asumsikan model Testimoni Anda juga punya accessor photo_url */}
                <img
                    className="h-20 w-20 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md"
                    src={testimoni.photo_url || 'https://via.placeholder.com/150'}
                    alt={testimoni.name}
                />
            </div>
            <p className="text-gray-600 dark:text-gray-300 italic flex-grow">"{testimoni.quote}"</p>
            <div className="mt-6">
                <p className="font-bold text-gray-900 dark:text-white">{testimoni.name}</p>
                <p className="text-sm text-gray-500 dark:text-gray-400">{testimoni.title}</p>
            </div>
        </div>
    );
}
