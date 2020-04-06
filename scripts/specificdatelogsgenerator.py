#!/usr/bin/python


import sys, os, random, csv, glob, re, fnmatch, urllib
#from datetime import datetime, timedelta
from zipfile import ZipFile
import datetime
import pandas as pd

import random, string

fileLimit = 1000000

BASE_DIR = os.path.abspath(os.path.join(__file__ ,"../../"))
#sys.exit()

def fnGenerateRandomString(stringLength=10):
	"""Generate a random string of letters and digits """
	lettersAndDigits = string.ascii_letters + string.digits
	return ''.join(random.choice(lettersAndDigits) for i in range(stringLength))

def fnExtractMobileWiseHistory(mobileno,historyFile, logFile):
	#print "\nPanda Extract"
	#print "=>"+historyFile
	#print "=>Mobile :"+ mobileno
	try:
		#dF = pd.read_csv(historyFile, sep=",", encoding='utf-8', dtype='unicode', quoting=csv.QUOTE_NONE, error_bad_lines=False, index_col=False)
		dF = pd.read_csv(historyFile, sep=",", encoding='utf-8', dtype='unicode', index_col=False)
		#print dF['MOBILENO']
		#sys.exit()
	
		#dF.query('MOBILENO.str.contains(mobileno)')
		filteredDF = dF[(dF['MOBILENO'].str.contains(mobileno))]
		#filteredDF = dF[(dF['MOBILENO'].str.contains(mobileno+"|91"+mobileno))]
		#filteredDF = dF[[mobileno in x for x in dF['MOBILENO']]]
		#print filteredDF
		filteredDF.to_csv(logFile, mode='a+',index=None, header=False, encoding='utf-8')
	except :
		pass

	return "NA"

if len(sys.argv) > 1 :
	server = str(sys.argv[1])
	username = str(sys.argv[2])
	requesttype = str(sys.argv[3])
	requesttext = str(sys.argv[4])
	requesttext = str(urllib.unquote(requesttext))
	specificdate = str(sys.argv[5])
	

	zipFileName = BASE_DIR+"/generatedFiles/"+username+"_"+str(fnGenerateRandomString(16))+".zip"
	date, month, year = specificdate.split('-')

	filesforzipping = []
	logFileName = BASE_DIR+"/generatedFiles/"+username+"_"+str(fnGenerateRandomString(16))+".csv"

	#print logFileName

	#Add Header
	with open(logFileName, 'w') as outcsv:
		writer = csv.writer(outcsv)
		writer.writerow(["DATE","SENDERID","SEQUENCEID","MOBILENO","MESSAGE","GEN_TS","DLV_TS","MIS_STATUS"])

	folder = "/mis_reportlogs/"+str(month)+str(year)+"/"+server+"/"+str(date)+"/"+username+"/"
	#print folder

	#try:
	#files = glob.glob(folder+"/"+"/*.csv")
	files = [f for f in os.listdir(folder) if f.endswith('.csv')]
	#print files
	for filename in files:
		#print "\nFile Name : "+str(filename)
		if(requesttype == "CAMPAIGNWISE"):
			if fnmatch.fnmatch(filename, "*"+requesttext+"*.csv"):
				#print "File found : "+filename
				filesforzipping.append(folder+filename)
		elif(requesttype == "MOBILESEARCH"):
			fnExtractMobileWiseHistory(requesttext,folder+filename,logFileName)
		'''
		else:
			print "\nRequest not supported"
		'''
	#except Exception as e:
	#pass

	
	with ZipFile(zipFileName, 'w') as zipObj:
		if(requesttype == "CAMPAIGNWISE"):
			if len(filesforzipping) > 0:
				for filepath in filesforzipping:
					filename= filepath.split(os.sep)[-1]
					zipObj.write(filepath,filename)
				print os.path.basename(zipFileName)
			else:
				print "NO_FILE_AVAILABLE"
		
		elif(requesttype == "MOBILESEARCH"):
			filename= logFileName.split(os.sep)[-1]
			zipObj.write(logFileName,filename)
			print os.path.basename(zipFileName)
		else:
			print "INVALID_REQUEST"

else:
	print "INVALID_ACCESS"	
