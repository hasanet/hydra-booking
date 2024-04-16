export default function useDateFormat() {

    const Tfhb_Date = (dateString) => {
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
    }

    const Tfhb_Time = (timeString) => {
        const options = { hour: '2-digit', minute: '2-digit', hour12: true };
        const time = new Date(timeString);
        return time.toLocaleTimeString('en-US', options);
    }
    
    return { Tfhb_Date, Tfhb_Time }
}